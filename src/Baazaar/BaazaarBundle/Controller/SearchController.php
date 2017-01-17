<?php
namespace Baazaar\BaazaarBundle\Controller;
use Baazaar\BaazaarBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class SearchController extends Controller {


      public function searchAction($keyword) {

          $finder = $this->container->get('fos_elastica.finder.search.ads');
          $results = $finder->find($keyword);

          return $this->render('BaazaarBaazaarBundle:Search:results.html.twig', array(
              "results" => $results
          ));
      }

      public function searchLocationAction() {


          if(isset( $_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $finder = $this->container->get('fos_elastica.finder.search.place');
            $results = $finder->find($keyword. '*');

            $locations = array();
            foreach ($results as $result) {
                $locations[] = array(
                    'zip' => $result->getZipcode()->getZip(),
                    'name' =>  $result->getName(),
                    'id' => $result->getId()
                );
            }

            $response = new JsonResponse();
            $response->setData($locations);

            return $response;
          }
      }

}
