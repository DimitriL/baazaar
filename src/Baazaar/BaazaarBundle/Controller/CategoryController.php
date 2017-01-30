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

          $ESHelper = $this->get('baazaar.elasticSearchHelper');

          $result = $ESHelper->getAdsByCategoriesByFilters($categories);

          return $this->render('BaazaarBaazaarBundle:Page:index.html.twig', array(
              'ads' => $result['ads'],
              'facets' => $result['facets'],
              'filters' => $result['filters'],
              'nocategories' => true
          ));
      }


    public function createCategoriesAction() {


        $categories = array(
          'Playstation' => array(
            'Playstation 1',
            'Playstation 2',
            'Playstation 3',
            'Playstation 4',
            'Playstation Portable',
            'Playstation Vita'
          ),
          'Xbox' => array(
            'Xbox',
            'Xbox 360',
            'Xbox One'
          ),
          'Nintendo' => array(
            'Nintendo Game & Watch',
            'Nintendo Virtual Boy',
            'Nintendo Switch',
            'Nintendo Wii',
            'Nintendo Wii U',
            'Nintendo DS',
            'Nintendo 3DS',
            'Nintendo Gameboy',
            'Nintendo Gameboy Color',
            'Nintendo Gameboy Advance',
            'NES',
            'SNES',
            'Nintendo 64',
            'Nintendo Gamecube'
          ),
          'SEGA' => array(
            'Sega Master System',
            'Sega Genesis',
            'Sega Game Gear',
            'Sega Nomad',
            'Sega CD',
            'Sega Saturn',
            'Sega Dreamcast'
          ),
          'Atari' => array(
            'Atari 2600',
            'Atari 5200',
            'Atari 7800',
            'Atari Jaguar',
            'Atari Lynx'
          ),
          'Andere' => array()
        );


        foreach($categories as $parent => $children){
            $em = $this->getDoctrine()->getManager();
            $cat = new Category();
            $cat->setTitle($parent);
            $em->persist($cat);

            foreach($children as $child){
              $cate = new Category();
              $cate->setTitle($child);
              $cate->setParent($cat);
              $em->persist($cate);
            }

            $em->flush();
        }



       die();

        return $this->render('BaazaarBaazaarBundle:Page:about.html.twig', array());

    }

}
