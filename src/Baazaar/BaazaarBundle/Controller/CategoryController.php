<?php
namespace Baazaar\BaazaarBundle\Controller;
use Baazaar\BaazaarBundle\Controller\Controller;
use Baazaar\BaazaarBundle\Entity\Category;

class CategoryController extends Controller {
    
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $ads = $em->getRepository('BaazaarBaazaarBundle:Ad')->getAdsByCategoryId($id);
        
        return $this->render('BaazaarBaazaarBundle:Page:index.html.twig', array(
            'ads' => $ads
        )); 
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
