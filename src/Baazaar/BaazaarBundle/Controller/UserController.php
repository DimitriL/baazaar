<?php

namespace Baazaar\BaazaarBundle\Controller;

use Baazaar\BaazaarBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

use Baazaar\BaazaarBundle\Entity\Enquiry;
use Baazaar\BaazaarBundle\Form\EnquiryType;

use Baazaar\BaazaarBundle\Entity\Category;

class UserController extends Controller{

    public function dashboardAction($user_id) {

    }

    public function adsAction($user_id) {
        $this->enforceOwnerSecurityForProfile($user_id);

        $em = $this->getDoctrine()->getManager();
        $ads = $em->getRepository('BaazaarBaazaarBundle:Ad')->getAdsByUser($user_id);

        return $this->render('BaazaarBaazaarBundle:User:ads.html.twig', array(
            "ads" => $ads
        ));


    }

    public function inboxAction($user_id) {
      $this->enforceOwnerSecurityForProfile($user_id);
      return $this->render('BaazaarBaazaarBundle:User:inbox.html.twig', array(
      ));
    }

    public function favoritesAction($user_id) {
      $this->enforceOwnerSecurityForProfile($user_id);
      return $this->render('BaazaarBaazaarBundle:User:inbox.html.twig', array(
      ));
    }

    public function bidsAction($user_id) {
      $this->enforceOwnerSecurityForProfile($user_id);
      return $this->render('BaazaarBaazaarBundle:User:bids.html.twig', array(
      ));
    }

    public function settingsAction($user_id) {
      $this->enforceOwnerSecurityForProfile($user_id);
      return $this->render('BaazaarBaazaarBundle:User:settings.html.twig', array(
      ));
    }

}
