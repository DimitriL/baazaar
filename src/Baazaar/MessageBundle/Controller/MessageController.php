<?php
namespace Baazaar\MessageBundle\Controller;

use Baazaar\BaazaarBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends Controller {


    public function __construct() {}

    public function sendAction($ad_id) {
      $em = $this->getDoctrine()->getManager();
      $ad = $em->getRepository('BaazaarBaazaarBundle:Ad')->findOneBy(array('id' => $ad_id));

      //load message send form

      $composer = $this->get('fos_message.composer');
      $message = $composer->newThread()
        ->setSender($this->getUser())
        ->addRecipient($ad->getOwner())
        ->setSubject('Hi there')
        ->setBody('This is a test message')
        ->getMessage();


        $sender = $this->get('fos_message.sender');
        $sender->send($message);

        //attach thread to ad
        $thread = $message->getThread();
        $thread->setAd($ad);
        $em->flush();


        return $this->redirect($this->generateUrl('baazaar_baazaar_ad', array('slug' => $ad->getSlug())));

    }

}
