<?php

namespace Baazaar\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Model\ParticipantInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
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
     * @ORM\ManyToMany(targetEntity="Baazaar\BaazaarBundle\Entity\Ad")
     */
    protected $favorite_ads;

    /**
     * @ORM\OneToMany(targetEntity="Baazaar\BaazaarBundle\Entity\AdReport", mappedBy="reporter")
     */
    protected $ad_reports;

    /**
     * @ORM\OneToMany(targetEntity="Baazaar\BaazaarBundle\Entity\Bid", mappedBy="user")
     */
    protected $bids;

    /**
     * @ORM\OneToMany(targetEntity="Baazaar\MediaBundle\Entity\File", mappedBy="owner")
     */
    protected $files;

    /**
     * Many User have Many Phonenumbers.
     * @ORM\ManyToMany(targetEntity="Baazaar\BaazaarBundle\Entity\Review")
     * @ORM\JoinTable(name="users_reviews",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="review_id", referencedColumnName="id", unique=true)}
     *      )
     */
    protected $reviews;

    /**
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebook_id;

    /**
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebook_access_token;

    public function __construct() {
        $this->ads = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->favorite_ads = new ArrayCollection();
        $this->ad_reports = new ArrayCollection();
        $this->reviews = new ArrayCollection();
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

    /**
     * Add ad
     *
     * @param \Baazaar\BaazaarBundle\Entity\Ad $ad
     *
     * @return User
     */
    public function addAd(\Baazaar\BaazaarBundle\Entity\Ad $ad)
    {
        $this->ads[] = $ad;

        return $this;
    }

    /**
     * Remove ad
     *
     * @param \Baazaar\BaazaarBundle\Entity\Ad $ad
     */
    public function removeAd(\Baazaar\BaazaarBundle\Entity\Ad $ad)
    {
        $this->ads->removeElement($ad);
    }

    /**
     * Add favoriteAd
     *
     * @param \Baazaar\BaazaarBundle\Entity\Ad $favoriteAd
     *
     * @return User
     */
    public function addFavoriteAd(\Baazaar\BaazaarBundle\Entity\Ad $favoriteAd)
    {
        $this->favorite_ads[] = $favoriteAd;

        return $this;
    }

    /**
     * Remove favoriteAd
     *
     * @param \Baazaar\BaazaarBundle\Entity\Ad $favoriteAd
     */
    public function removeFavoriteAd(\Baazaar\BaazaarBundle\Entity\Ad $favoriteAd)
    {
        $this->favorite_ads->removeElement($favoriteAd);
    }

    /**
     * Get favoriteAds
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavoriteAds()
    {
        return $this->favorite_ads;
    }

    /**
     * Add bid
     *
     * @param \Baazaar\BaazaarBundle\Entity\Bid $bid
     *
     * @return User
     */
    public function addBid(\Baazaar\BaazaarBundle\Entity\Bid $bid)
    {
        $this->bids[] = $bid;

        return $this;
    }

    /**
     * Remove bid
     *
     * @param \Baazaar\BaazaarBundle\Entity\Bid $bid
     */
    public function removeBid(\Baazaar\BaazaarBundle\Entity\Bid $bid)
    {
        $this->bids->removeElement($bid);
    }

    /**
     * Get bids
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBids()
    {
        return $this->bids;
    }

    /**
     * Add file
     *
     * @param \Baazaar\MediaBundle\Entity\File $file
     *
     * @return User
     */
    public function addFile(\Baazaar\MediaBundle\Entity\File $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param \Baazaar\MediaBundle\Entity\File $file
     */
    public function removeFile(\Baazaar\MediaBundle\Entity\File $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Add adReport
     *
     * @param \Baazaar\BaazaarBundle\Entity\AdReport $adReport
     *
     * @return User
     */
    public function addAdReport(\Baazaar\BaazaarBundle\Entity\AdReport $adReport)
    {
        $this->ad_reports[] = $adReport;

        return $this;
    }

    /**
     * Remove adReport
     *
     * @param \Baazaar\BaazaarBundle\Entity\AdReport $adReport
     */
    public function removeAdReport(\Baazaar\BaazaarBundle\Entity\AdReport $adReport)
    {
        $this->ad_reports->removeElement($adReport);
    }

    /**
     * Get adReports
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdReports()
    {
        return $this->ad_reports;
    }

    /**
     * Add review
     *
     * @param \Baazaar\BaazaarBundle\Entity\Review $review
     *
     * @return User
     */
    public function addReview(\Baazaar\BaazaarBundle\Entity\Review $review)
    {
        $this->reviews[] = $review;

        return $this;
    }

    /**
     * Remove review
     *
     * @param \Baazaar\BaazaarBundle\Entity\Review $review
     */
    public function removeReview(\Baazaar\BaazaarBundle\Entity\Review $review)
    {
        $this->reviews->removeElement($review);
    }

    /**
     * Get reviews
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set facebookAccessToken
     *
     * @param string $facebookAccessToken
     *
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebookAccessToken
     *
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }
}
