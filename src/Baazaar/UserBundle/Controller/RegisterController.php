<?php

namespace Baazaar\UserBundle\Controller;

use Baazaar\BaazaarBundle\Controller\Controller;
use Baazaar\UserBundle\Entity\User;
use Baazaar\UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class RegisterController extends Controller implements ContainerAwareInterface{

    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Register action
     * @param Request $request
     * @return type
     */
    public function registerAction(Request $request) {
        $user = new User();
        $form = $this->createForm(UserType::Class, $user);


        $form->handleRequest($request);
        //on a GET request, $form->isSubmitted() returns false.
        if($form->isValid() && $form->isSubmitted()) {

           $this->createNewUser($user);
           $this->authenticateUser($user);

            //add flash notice
            return $this->redirect($this->generateUrl('baazaar_baazaar_homepage')); //redirect to homepage
        }

        return $this->render('BaazaarUserBundle:Security:register.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Function to handle the new user routine
     * @param type $user
     */
    private function createNewUser($user) {
        // Persist the user entity to the database
        $em = $this->getDoctrine()->getManager();
        $user->setPassword($this->encodePassword($user, $user->getPlainPassword()));
        $em->persist($user);
        $em->flush();
    }

    private function authenticateUser(User $user) {
        $proveiderKey = 'secured_area';
        $token = new UsernamePasswordToken($user, null, $proveiderKey, $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
    }

    /**
     * TODO: => put this in helper class
     */
    private function encodePassword(User $user, $plainPassword) {
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }


}
