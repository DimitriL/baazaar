<?php

namespace Baazaar\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zipcode
 *
 * @ORM\Table(name="zipcode")
 * @ORM\Entity(repositoryClass="Baazaar\LocationBundle\Repository\ZipcodeRepository")
 */
class Zipcode
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
     * @ORM\Column(name="zip", type="string", length=255)
     */
     private $zip;

     /**
      * @ORM\ManyToOne(
      *          targetEntity="Baazaar\LocationBundle\Entity\Province",
      *          inversedBy="zipcodes")
      */
     private $province;

     /**
      * @ORM\OneToMany(targetEntity="Baazaar\LocationBundle\Entity\Place", mappedBy="zipcode")
      */
     private $places;


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
     * Constructor
     */
    public function __construct()
    {
        $this->places = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return Zipcode
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set province
     *
     * @param \Baazaar\LocationBundle\Entity\Province $province
     *
     * @return Zipcode
     */
    public function setProvince(\Baazaar\LocationBundle\Entity\Province $province = null)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * Get province
     *
     * @return \Baazaar\LocationBundle\Entity\Province
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Add place
     *
     * @param \Baazaar\LocationBundle\Entity\Place $place
     *
     * @return Zipcode
     */
    public function addPlace(\Baazaar\LocationBundle\Entity\Place $place)
    {
        $this->places[] = $place;

        return $this;
    }

    /**
     * Remove place
     *
     * @param \Baazaar\LocationBundle\Entity\Place $place
     */
    public function removePlace(\Baazaar\LocationBundle\Entity\Place $place)
    {
        $this->places->removeElement($place);
    }

    /**
     * Get places
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlaces()
    {
        return $this->places;
    }
}
