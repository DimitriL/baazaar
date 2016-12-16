<?php

namespace Baazaar\BaazaarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Ad
 *
 * @ORM\Table(name="ad")
 * @ORM\Entity(repositoryClass="Baazaar\BaazaarBundle\Entity\Repository\AdRepository")
 */
class Ad
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="string", length=255)
     */
    private $body;
    
    /**
     * @ORM\ManyToOne(
     *          targetEntity="Baazaar\UserBundle\Entity\User",
     *          inversedBy="ads")
     */
    private $owner;
    
    /**
     * @Gedmo\Slug(fields={"title"}, updatable=false)
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    protected $slug;
    
    /**
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(type="datetime")
    */
    private $createdAt;
    
    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    
    /**
     * @ORM\ManyToMany(targetEntity="Baazaar\MediaBundle\Entity\File")
     */
    protected $files;
    
    /**
     * @ORM\ManyToMany(targetEntity="Baazaar\BaazaarBundle\Entity\Category")
     */
    protected $categories;
    
    private $categoriesList;    
    private $uploads;
    
    public function __construct() {
        $this->files = new ArrayCollection();
        $this->categories = new ArrayCollection();
        
        //uploads needs to be an array
        $this->uploads = Array();
        
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
     * Set title
     *
     * @param string $title
     *
     * @return Ad
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Ad
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set owner
     *
     * @param \Baazaar\UserBundle\Entity\User $owner
     *
     * @return Ad
     */
    public function setOwner(\Baazaar\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Baazaar\UserBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }
    
    public function getSlug() {
        return $this->slug;
    }
    
    public function setSlug($slug) {
        $this->slug = $slug;
    }
    
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
       return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
       return $this->updatedAt;
    }
    
    public function getFiles() {
        return $this->files;
    }
    
    public function getUploads() {
        return $this->uploads;
    }
    
    public function setUploads($uploads) {
        
        $this->uploads = $uploads;
    }
    
    public function getCategoriesList() {
        return $this->categoriesList;
    }
    
    public function setCategoriesList($categoriesList) {
        
        $this->categoriesList = $categoriesList;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Ad
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Ad
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Add file
     *
     * @param \Baazaar\MediaBundle\Entity\File $file
     *
     * @return Ad
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
     * Add category
     *
     * @param \Baazaar\BaazaarBundle\Entity\Category $category
     *
     * @return Ad
     */
    public function addCategory(\Baazaar\BaazaarBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Baazaar\BaazaarBundle\Entity\Category $category
     */
    public function removeCategory(\Baazaar\BaazaarBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }
    
    /**
     * Set categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }
}