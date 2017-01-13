<?php
namespace Baazaar\BaazaarBundle\Controller;
use Baazaar\BaazaarBundle\Controller\Controller;
use Baazaar\BaazaarBundle\Entity\Category;

class CategoryController extends Controller {

      //convert id to slug
      public function showAction($id) {
          //add current category
          $categories = array((int)$id);

          //add all children
          $em = $this->getDoctrine()->getManager();
          $category = $em->getRepository('BaazaarBaazaarBundle:Category')->find($id);
          $children = $em->getRepository('BaazaarBaazaarBundle:Category')->getChildren($category);

          foreach($children as $child) {  $categories[] = $child->getId(); }

          $result = $this->getAdsByCategories($categories);
          return $this->render('BaazaarBaazaarBundle:Page:index.html.twig', array(
              'ads' => $result['ads'],
              'facets' => $result['facets'],
              'filters' => $result['filters']
          ));
      }

      /**
       * get ads by categories from elasticSearch
       */
     private function getAdsByCategories($categories) {
        $finder = $this->container->get('fos_elastica.finder.search.ads');

        if(isset($_GET['filter'])){
          $get_filters = $_GET['filter'];
          $filters = $this->parseFilters($get_filters);
        }

        //add category
        $filters['categories.id'] =  array(
           'type' => 'nested:term',
           'value' => $categories,
           'path' => 'categories'
        );


         /*$filters = array(
            'categories.id' => array(
              'type' => 'nested:term',
              'value' => $categories,
              'path' => 'categories'
            ),
            'delivery_method' => array(
              'type' => 'term',
              'value' => ['send']
            ),
            'object_status' => array(
              'type' => 'term',
              'value' => ['new']
            ),
            'price.price_type' => array(
              'type' => 'nested:term',
              'value' => ['consent'],
              'path' => 'price'
            ),
            'price.amount' => array(
              'type' => 'nested:range',
              'value' => '50.0-150.0',
              'range_value' => array(
                'gte' => 50,
                'lte' => 150
              ),
              'path' => 'price'
            ),
        );*/

        $query = new \Elastica\Query();

        //add filters to query
        $filterQuery = $this->createFilters($filters);
        $query->setQuery($filterQuery);
        //todo: add sorting

        //add facets to query
        $facets = $this->createFacets();
        foreach($facets as $facet) {$query->addAggregation($facet);}

        $facetQuery = clone $query;
        $facetQuery->setSize(0);

        //search straight onto index to get facet results
        $results = $this->get('fos_elastica.index.search.ads')->search($facetQuery);
        //doctrine orm wrapper to get objects out of database
        $ads = $finder->find($query);
        
        return array(
            'ads' => $ads,
            'facets' => $results->getAggregations(),
            'filters' => $filters
        );
    }

    private function parseFilters($filters) {
        //closure => function for use in its own scope
        $parseFilterValues = function ($values) {
            $vals = array();
            foreach ($values as $key => $value) {
                $vals[] = $value;
            }
            return $vals;
        };

        $parsed_filters = array();
        foreach($filters as $filter => $filter_values) {
            switch($filter) {
                case 'delivery_method': case 'object_status':
                      $parsed_filters[$filter] = array(
                          'type' => 'term',
                          'value' => $parseFilterValues($filter_values)
                      );
                      break;
                case 'price_range':
                      foreach($filter_values as $filter_key => $values) {
                           list($from , $to) = explode('-', $values[0]);
                            $parsed_filters[$filter_key] = array(
                                'type' => 'nested:range',
                                'range_value' => array(
                                    'gte' => (int)$from,
                                    'lte' => (int)$to
                                ),
                                'value' => $parseFilterValues($values),
                                'path' => 'price'
                            );
                      }
                      break;
                 case 'price_type':
                      foreach($filter_values as $filter_key => $values) {
                          $parsed_filters[$filter_key] = array(
                              'type' => 'nested:term',
                              'value' =>  $parseFilterValues($values),
                              'path' => 'price'
                          );
                      }
                      break;

            }
        }
        return $parsed_filters;
    }

    public function createFilters($filters) {
        $boolQuery = new \Elastica\Query\BoolQuery();

        foreach($filters as $field_name => $filter) {
            switch($filter['type']){
                case 'term':
                    $termQuery = $this->createTermFilter($field_name,  $filter['value']);
                    $boolQuery->addMust($termQuery);
                    break;
                case 'nested:term':
                    $nestedQuery = $this->createNestedTermFilter($field_name, $filter['value'], $filter['path']);
                    $boolQuery->addMust($nestedQuery);
                    break;
                case 'nested:range':
                    $nestedQuery = $this->createNestedRangeFilter($field_name, $filter['range_value'], $filter['path']);
                    $boolQuery->addMust($nestedQuery);
                    break;
            }
        }

        return $boolQuery;
    }

    private function createNestedTermFilter($field_name, $value, $path){

      $nestedQuery = new \Elastica\Query\Nested();
      $nestedQuery->setPath($path);

      $termQuery = $this->createTermFilter($field_name, $value);

      $boolQuery2 = new \Elastica\Query\BoolQuery();
      $boolQuery2->addMust($termQuery);

      $nestedQuery->setQuery($boolQuery2);
      return $nestedQuery;
    }

    private function createNestedRangeFilter($field_name, $value, $path) {
      $nestedQuery = new \Elastica\Query\Nested();
      $nestedQuery->setPath($path);

      $termQuery = $this->createRangeFilter($field_name, $value);

      $boolQuery2 = new \Elastica\Query\BoolQuery();
      $boolQuery2->addMust($termQuery);

      $nestedQuery->setQuery($boolQuery2);
      return $nestedQuery;
    }

    private function createTermFilter($field_name, $value) {
      $termQuery = new \Elastica\Query\Terms();
      $termQuery->setTerms($field_name, $value);
      return $termQuery;
    }

    private function createRangeFilter($field_name, $value) {
      $rangeQuery = new \Elastica\Query\Range($field_name, $value);
      return $rangeQuery;
    }

    public function createFacets() {

      $facets = array();
      $facets['object_status'] = $this->createObjectStatusFacet();
      $facets['delivery_method'] = $this->createDeliveryMethodFacet();
      $facets['price_type'] = $this->createPriceTypeFacet();
      $facets['price_range'] = $this->createPriceRangeFacet();

      return $facets;


    }

    private function createObjectStatusFacet() {
        return $this->createTermAggregation('object_status');
    }

    private function createDeliveryMethodFacet() {
        return $this->createTermAggregation('delivery_method');
    }

    private function createPriceTypeFacet() {
      return $this->createNestedAggregation('price_type', 'price', array('price.price_type' => 'term_agg'));
    }

    private function createPriceRangeFacet() {
      return $this->createNestedAggregation('price_range', 'price', array('price.amount' => 'range_agg'), array('ranges' => array(
          0 => array(
            'from' => 0,
            'to' => 50
          ),
          1 => array(
            'from' => 50,
            'to' => 150
          )
          ,
          2 => array(
            'from' => 150,
            'to' => 500
          )
          ,
          3 => array(
            'from' => 500,
            'to' => 1000
          )
          ,
          4 => array(
            'from' => 1000,
            'to' => 1000000000
          )

      )));
    }

    private function createNestedAggregation($nested_field, $nested_field_path, $fields = array(), $options = array(), $min_doc_count = 0) {
          $nestedAggregation = new \Elastica\Aggregation\Nested($nested_field, $nested_field_path);

          foreach($fields as $field_name => $agg_type){
              switch($agg_type) {
                case 'term_agg':
                    $aggregation = $this->createTermAggregation($field_name, $min_doc_count);
                    break;
                case 'range_agg':
                    $aggregation = $this->createRangeAggregation($field_name, $options['ranges']);
                    break;
                default:
                    $aggregation = $this->createTermAggregation($field_name, $min_doc_count);
              }
          }

          $nestedAggregation->addAggregation($aggregation);
          return $nestedAggregation;
    }

    private function createRangeAggregation($field, $ranges, $min_doc_count = 0) {
          $aggregation = new \Elastica\Aggregation\Range($field);
          $aggregation->setField($field);

          foreach($ranges as $range) {
              $aggregation->addRange($range['from'], $range['to']);
          }

          return $aggregation;

    }

    private function createTermAggregation($field, $min_doc_count = 0) {
          $aggregation = new \Elastica\Aggregation\Terms($field);
          $aggregation->setField($field);
          $aggregation->setMinimumDocumentCount($min_doc_count);
          return $aggregation;
    }

    public function createCategoryFacet() {
      $query = new \Elastica\Query(new \Elastica\Query\MatchAll());

      $nestedAggregation = new \Elastica\Aggregation\Nested('categories', 'categories');

      // Simple aggregation (based on tags, we get the doc_count for each tag)
      $categoriesAggregation = new \Elastica\Aggregation\Terms('category');
      $categoriesAggregation->setField('categories.id');
      $categoriesAggregation->setMinimumDocumentCount(0);

      $nestedAggregation->addAggregation($categoriesAggregation);
      $query->addAggregation($nestedAggregation);

      // we don't need the search results, only statistics
      $query->setSize(0);

      $results = $this->get('fos_elastica.index.search.ads')->search($query);
      var_dump($results->getAggregations());die();
    }

    public function createCategoriesAction() {

        $em = $this->getDoctrine()->getManager();
        $playstation = new Category();
        $playstation->setTitle('Playstation');
        $em->persist($playstation);

        $playstation1 = new Category();
        $playstation1->setTitle('Playstation 1');
        $playstation1->setParent($playstation);
        $em->persist($playstation1);

        $playstation2 = new Category();
        $playstation2->setTitle('Playstation 2');
        $playstation2->setParent($playstation);
        $em->persist($playstation2);

        $playstation3 = new Category();
        $playstation3->setTitle('Playstation 3');
        $playstation3->setParent($playstation);
        $em->persist($playstation3);

        $em->flush();

       var_dump($playstation);
       die();

        return $this->render('BaazaarBaazaarBundle:Page:about.html.twig', array());

    }

}
