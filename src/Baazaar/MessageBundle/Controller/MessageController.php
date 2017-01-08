<?php
namespace Baazaar\MessageBundle\Controller;

use Baazaar\BaazaarBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Baazaar\MessageBundle\Entity\Message;
use Baazaar\MessageBundle\Form\MessageType;

class MessageController extends Controller {


    public function __construct() {}

    public function sendAction($ad_id, Request $request) {
      $em = $this->getDoctrine()->getManager();
      $ad = $em->getRepository('BaazaarBaazaarBundle:Ad')->findOneBy(array('id' => $ad_id));


      $msg = new Message();
      $form = $this->createForm(MessageType::class, $msg);

      if ($request->isMethod('POST')) {
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
          
          $composer = $this->get('fos_message.composer');
          $message = $composer->newThread()
            ->setSender($this->getUser())
            ->addRecipient($ad->getOwner())
            ->setSubject('Reactie op zoekertje: ' . $ad->getTitle()) //Reactie op 'advertentienaam'
            ->setBody($msg->getBody())
            ->getMessage();


            $sender = $this->get('fos_message.sender');
            $sender->send($message);

            //attach thread to ad
            $thread = $message->getThread();
            $thread->setAd($ad);
            $em->flush();

           //redirect to ad
           return $this->redirect($this->generateUrl('baazaar_baazaar_ad', array('slug' => $ad->getSlug())));
        }
      }
      return $this->render('BaazaarMessageBundle:Message:send.html.twig', array(
          "ad" => $ad,
          "form" => $form->createView()
      ));
    }

}
