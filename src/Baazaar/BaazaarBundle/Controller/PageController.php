<?php

namespace Baazaar\BaazaarBundle\Controller;

use Baazaar\BaazaarBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

use Baazaar\BaazaarBundle\Entity\Enquiry;
use Baazaar\BaazaarBundle\Form\EnquiryType;

use Baazaar\BaazaarBundle\Entity\Category;

class PageController extends Controller{
    /**
     * Homepage
     * @return type
     */
    public function indexAction() {
        //$this->enforceUserSecurity(); //you can also do this through access_controle in security.yml

        $em = $this->getDoctrine()->getManager();

        $ads = $em->getRepository('BaazaarBaazaarBundle:Ad')->getLatestAds();

        return $this->render('BaazaarBaazaarBundle:Page:index.html.twig', array(
            'ads' => $ads
        ));
    }

    /**
     * About page
     * @return type
     */
    public function aboutAction() {
        return $this->render('BaazaarBaazaarBundle:Page:about.html.twig', array());
    }

    /**
     * Contact page
     * @param Request $request
     * @return type
     */
    public function contactAction(Request $request) {
        $enquiry = new Enquiry();
        $form = $this->createForm(EnquiryType::class, $enquiry);

        if($request->getMethod() == 'POST'){
           $form->handleRequest($request);
            if($form->isValid()) {

                $this->addFlash('baazaar-notice', 'Your contact enquiry was successfully sent. Thank you!');
                return $this->redirect($this->generateUrl('baazaar_baazaar_contact'));
            }
        }

        return $this->render('BaazaarBaazaarBundle:Page:contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function sidebarAction() {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('BaazaarBaazaarBundle:Category')->childrenHierarchy(
                null,
                false,
                array(
                    'decorate' => true,
                    'rootOpen' => '<ul>',
                    'rootClose' => '</ul>',
                    'childOpen' => '<li>',
                    'childClose' => '</li>',
                    'nodeDecorator' => function($node) {
                        return '<a href="'. $this->generateUrl('baazaar_baazaar_category', array('id' => $node['id'])).'">'.$node['title'].'</a>';
                    }
                )
        );

         return $this->render('BaazaarBaazaarBundle:Page:sidebar.html.twig', array(
            'categories' => $categories
        ));
    }


}
