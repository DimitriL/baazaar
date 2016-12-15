<?php

namespace Baazaar\UserBundle\Controller;

use Baazaar\BaazaarBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Baazaar\UserBundle\Entity\User;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends Controller {

    public function loginAction(Request $request) {
        $session = $request->getSession();

        $error = $session->get(Security::AUTHENTICATION_ERROR);
        $session->remove(Security::ACCESS_DENIED_ERROR);

        return $this->render(
            'BaazaarUserBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(Security::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }

    /**
     * function to login user is correct data is given
     * @param Request $request
     * @return RedirectResponse
     */
    public function loginCheckAction(Request $request) {
        $em = $this->getDoctrine()->getEntityManager();
        //check if user exist in database
        $user = $em->getRepository('BaazaarUserBundle:User')->findByUsername($request->get('_username'));
        if(!empty($user)){
            $password = $this->encodePassword($user, $request->get('_password'));
            if( $password == $user->getPassword()) {
                $token = new UsernamePasswordToken($user, $password, 'secured_area', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);
                return new RedirectResponse($this->generateUrl('baazaar_baazaar_homepage'));
            } else {
                $this->addFlash('notice-login', 'Password is incorrect!');
                return new RedirectResponse($this->generateUrl('baazaar_user_login'));
            }
        } else {
              $this->addFlash('notice-login', 'Username does not exist');
              return new RedirectResponse($this->generateUrl('baazaar_user_login'));
        }
    }

    /**
     * Logout action
     * @return RedirectResponse
     */
    public function logoutAction() {
        $this->get('security.token_storage')->setToken(null);
        $this->get('request')->getSession()->invalidate();
        return new RedirectResponse($this->generateUrl('baazaar_baazaar_homepage'));
    }

    /**
     * TODO: => put this in helper class
     */
    private function encodePassword(User $user, $plainPassword) {
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }
}
