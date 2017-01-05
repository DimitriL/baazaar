<?php

namespace Baazaar\UserBundle\Entity;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Doctrine\ORM\EntityManager;

use Baazaar\UserBundle\Entity\User;


class OAuthUserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface {


  protected $em;

  public function __construct(EntityManager $entityManager){
    $this->em = $entityManager;
  }

  /**
   * {@inheritDoc}
   */
  public function loadUserByUsername($username)
  {
      return new OAuthUser($username);
  }

  /**
   * {@inheritdoc}
   */
  public function loadUserByOAuthUserResponse(UserResponseInterface $response)
  {
      //user passes through here if he is authenticated with oauth
      $user = $this->em->getRepository('BaazaarUserBundle:User')->findOneBy(array('email' => $response->getEmail()));

      if(!empty($user)){
          //update facebook info when user was first registered by the normal flow
          if(empty($user->getFacebookId())) {
            $user->setFacebookId($response->getUsername());
            $user->setFacebookAccessToken($response->getAccessToken());

            $this->em->merge($user);
            $this->em->flush();
          }

      } else{
        //create new user base on oauth credentials
        $new_user = new User();
        $new_user->setUsername($response->getNickname());
        $new_user->setEmail($response->getEmail());
        $new_user->setFacebookId($response->getUsername());
        $new_user->setFacebookAccessToken($response->getAccessToken());

        $this->em->persist($user);
        $this->em->flush();


        $user = $new_user;
      }

      return $user;

  }

  /**
   * {@inheritDoc}
   */
  public function refreshUser(UserInterface $user)
  {
      if (!$this->supportsClass(get_class($user))) {
          throw new UnsupportedUserException(sprintf('Unsupported user class "%s"', get_class($user)));
      }

      return $this->loadUserByUsername($user->getUsername());
  }

  /**
   * {@inheritDoc}
   */
  public function supportsClass($class)
  {
      return $class === 'Baazaar\\UserBundle\\Entity\\User';
  }
}
