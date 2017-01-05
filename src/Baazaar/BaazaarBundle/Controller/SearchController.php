<?php
namespace Baazaar\BaazaarBundle\Controller;
use Baazaar\BaazaarBundle\Controller\Controller;

class SearchController extends Controller {


      public function searchAction($keyword) {

          $finder = $this->container->get('fos_elastica.finder.search.ads');
          $results = $finder->find($keyword);

          return $this->render('BaazaarBaazaarBundle:Search:results.html.twig', array(
              "results" => $results
          ));
      }

}
