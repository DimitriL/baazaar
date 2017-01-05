<?php

namespace Baazaar\UserBundle\Entity;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Doctrine\ORM\EntityManager;


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
      var_dump(get_class_methods($response));

      $user = $this->em->getRepository('BaazaarUserBundle:User')->findOneBy(array('email' => $response->getEmail()));

      if(!empty($user)){
        //load and authenticate this user
      } else{

        //create new user base on oauth credentials
        var_dump($response->getUsername());
        var_dump($response->getNickname());
        var_dump($response->getFirstName());
        var_dump($response->getLastName());
        var_dump($response->getEmail());
      }

    die();



      $username = $response->getUsername(); //unique identifier

      return $this->loadUserByUsername($response->getNickname());
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
