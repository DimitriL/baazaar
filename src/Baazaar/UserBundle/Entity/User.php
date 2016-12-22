<?php

namespace Baazaar\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Model\ParticipantInterface;
use Serializable;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Baazaar\UserBundle\Entity\Repository\UserRepository")
 * @UniqueEntity(fields="username", message="That username is taken!")
 * @UniqueEntity(fields="email", message="That email is taken!")
 */
class User implements AdvancedUserInterface, Serializable, ParticipantInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=3, minMessage="Give us at least 3 characters!")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = array();

    /**
    * @var bool
    *
    * @ORM\Column(type="boolean")
    */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     *@Assert\NotBlank()
     * @Assert\Regex(
     *      pattern="/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/",
     *      message="Use 1 upper case letter, 1 lower case letter, and 1 number"
     * )
     */
    private $plainPassword;

    /**
     * @ORM\OneToMany(targetEntity="Baazaar\BaazaarBundle\Entity\Ad", mappedBy="owner")
     */
    protected $ads;

    /**
     * @ORM\OneToMany(targetEntity="Baazaar\BaazaarBundle\Entity\Bid", mappedBy="user")
     */
    protected $bids;


    /**
     * @ORM\OneToMany(targetEntity="Baazaar\MediaBundle\Entity\File", mappedBy="owner")
     */
    protected $files;

    public function __construct() {
        $this->ads = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->setSalt();
        $this->setIsActive(true);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles() {
        $roles = $this->roles;
        $roles[] =  'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles) {
        $this->roles = $roles;

        // allows for chaining
        return $this;
    }

    public function eraseCredentials() {
        $this->setPlainPassword(null);
    }

    public function getSalt() {
        return $this->salt;
    }

    public function setSalt() {
        $this->salt = md5(time());
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->getIsActive();
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function serialize() {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    public function unserialize($serialized) {
        list (
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized);
    }

    public function getPlainPassword() {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword) {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getAds() {
        return $this->ads;
    }

    public function getFiles() {
        return $this->files;
    }
}
