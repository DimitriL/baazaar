<?php

namespace Baazaar\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Province
 *
 * @ORM\Table(name="province")
 * @ORM\Entity(repositoryClass="Baazaar\LocationBundle\Repository\ProvinceRepository")
 */
class Province
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
      *          targetEntity="Baazaar\LocationBundle\Entity\Country",
      *          inversedBy="provinces")
      */
     private $country;

     /**
      * @ORM\OneToMany(targetEntity="Baazaar\LocationBundle\Entity\Zipcode", mappedBy="province")
      */
     private $zipcodes;


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
        $this->zipcodes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Province
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
     * Set country
     *
     * @param \Baazaar\LocationBundle\Entity\Country $country
     *
     * @return Province
     */
    public function setCountry(\Baazaar\LocationBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Baazaar\LocationBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Add zipcode
     *
     * @param \Baazaar\LocationBundle\Entity\Zipcode $zipcode
     *
     * @return Province
     */
    public function addZipcode(\Baazaar\LocationBundle\Entity\Zipcode $zipcode)
    {
        $this->zipcodes[] = $zipcode;

        return $this;
    }

    /**
     * Remove zipcode
     *
     * @param \Baazaar\LocationBundle\Entity\Zipcode $zipcode
     */
    public function removeZipcode(\Baazaar\LocationBundle\Entity\Zipcode $zipcode)
    {
        $this->zipcodes->removeElement($zipcode);
    }

    /**
     * Get zipcodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getZipcodes()
    {
        return $this->zipcodes;
    }
}
