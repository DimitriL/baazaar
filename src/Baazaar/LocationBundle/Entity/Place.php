<?php

namespace Baazaar\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Place
 *
 * @ORM\Table(name="place")
 * @ORM\Entity(repositoryClass="Baazaar\LocationBundle\Repository\PlaceRepository")
 */
class Place
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(
     *          targetEntity="Baazaar\LocationBundle\Entity\Zipcode",
     *          inversedBy="places")
     */
    private $zipcode;

    /**
     * @ORM\OneToMany(targetEntity="Baazaar\BaazaarBundle\Entity\Ad", mappedBy="location")
     */
    private $ads;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Place
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set zipcode
     *
     * @param \Baazaar\LocationBundle\Entity\Zipcode $zipcode
     *
     * @return Place
     */
    public function setZipcode(\Baazaar\LocationBundle\Entity\Zipcode $zipcode = null)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return \Baazaar\LocationBundle\Entity\Zipcode
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ads = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ad
     *
     * @param \BaazaarLocationBundle\Entity\Ad $ad
     *
     * @return Place
     */
    public function addAd(\Baazaar\BaazaarBundle\Entity\Ad $ad)
    {
        $this->ads[] = $ad;

        return $this;
    }

    /**
     * Remove ad
     *
     * @param \BaazaarLocationBundle\Entity\Ad $ad
     */
    public function removeAd(\Baazaar\BaazaarBundle\Entity\Ad $ad)
    {
        $this->ads->removeElement($ad);
    }

    /**
     * Get ads
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAds()
    {
        return $this->ads;
    }
}
