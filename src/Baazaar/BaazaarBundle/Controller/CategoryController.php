<?php
namespace Baazaar\BaazaarBundle\Controller;
use Baazaar\BaazaarBundle\Controller\Controller;
use Baazaar\BaazaarBundle\Entity\Category;

class CategoryController extends Controller {

      //convert id to slug
      public function showAction($id) {
          //add current category
          $categories = array($id);

          //add all children
          $em = $this->getDoctrine()->getManager();
          $category = $em->getRepository('BaazaarBaazaarBundle:Category')->find($id);
          $children = $em->getRepository('BaazaarBaazaarBundle:Category')->getChildren($category);

          foreach($children as $child) {  $categories[] = $child->getId(); }

          $result = $this->getAdsByCategories($categories);
          return $this->render('BaazaarBaazaarBundle:Page:index.html.twig', array(
              'ads' => $result['ads'],
              'facets' => $result['facets']
          ));
      }

      /**
       * get ads by categories from elasticSearch
       */
     private function getAdsByCategories($categories) {
        $finder = $this->container->get('fos_elastica.finder.search.ads');

        $nestedQuery = new \Elastica\Query\Nested();
        $nestedQuery->setPath('categories');

        $fieldQuery = new \Elastica\Query\Terms();
        $fieldQuery->setTerms('categories.id', $categories);

        $boolQuery2 = new \Elastica\Query\BoolQuery();
        $boolQuery2->addMust($fieldQuery);

        $nestedQuery->setQuery($boolQuery2);

        $boolQuery = new \Elastica\Query\BoolQuery();
        $boolQuery->addMust($nestedQuery);

        $query = new \Elastica\Query($boolQuery);

        $facets = $this->createFacets();

        foreach($facets as $facet) {
            $query->addAggregation($facet);
        }

        $results = $this->get('fos_elastica.index.search.ads')->search($query);
        $ads = $finder->find($query);
        //var_dump($results->getAggregations());die();
        return array(
            'ads' => $ads,
            'facets' => $results->getAggregations()
        );
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
