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

          $ads = $this->getAdsByCategories($categories);
          return $this->render('BaazaarBaazaarBundle:Page:index.html.twig', array(
              'ads' => $ads
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

        $ads = $finder->find($boolQuery);
        return $ads;
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
