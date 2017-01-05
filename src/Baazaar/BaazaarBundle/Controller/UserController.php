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

    /**
     * route function to show user Ads
     */
    public function adsAction($user_id) {
        $this->enforceOwnerSecurityForProfile($user_id);

        $em = $this->getDoctrine()->getManager();
        $ads = $em->getRepository('BaazaarBaazaarBundle:Ad')->getAdsByUser($user_id);

        return $this->render('BaazaarBaazaarBundle:User:ads.html.twig', array(
            "ads" => $ads
        ));

    }

    /**
     * route function to show user inbox messages
     */
    public function inboxAction($user_id) {
      $this->enforceOwnerSecurityForProfile($user_id);

      //this will only pick up threads not send to yourself
      $provider = $this->get('fos_message.provider');
      $threads = $provider->getInboxThreads();
      return $this->render('BaazaarBaazaarBundle:User:inbox.html.twig', array(
        'threads' => $threads
      ));
    }

    public function inboxThreadAction($user_id, $threadId){
        $this->enforceOwnerSecurityForProfile($user_id);

        $provider = $this->get('fos_message.provider');
        $thread = $provider->getThread($threadId);

        return $this->render('BaazaarBaazaarBundle:User:inbox-thread.html.twig', array(
          'thread' => $thread
        ));

    }

    /**
     * route function to show user favorite ads
     */
    public function favoritesAction($user_id) {
      $this->enforceOwnerSecurityForProfile($user_id);

      $user= $this->getUser();
      $favorites = $user->getFavoriteAds();

      return $this->render('BaazaarBaazaarBundle:User:favorites.html.twig', array(
        "ads" => $favorites
      ));
    }

    /**
     * route function to show user bids
     */
    public function bidsAction($user_id) {
      $this->enforceOwnerSecurityForProfile($user_id);

      $user= $this->getUser();
      $bids = $user->getBids();

      return $this->render('BaazaarBaazaarBundle:User:bids.html.twig', array(
          'bids' => $bids
      ));
    }

    /**
     * route function to show user settings
     */
    public function settingsAction($user_id) {
      $this->enforceOwnerSecurityForProfile($user_id);
      return $this->render('BaazaarBaazaarBundle:User:settings.html.twig', array(
      ));
    }

}
