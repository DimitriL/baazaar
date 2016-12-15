<?php

namespace Baazaar\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="Baazaar\MediaBundle\Entity\Repository\FileRepository")
 */
class File
{
    /**
    * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
    * @Assert\File() 
    */
    private $file;    
    
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
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename;

    /**
     * @var integer
     *
     * @ORM\Column(name="filesize", type="integer")
     */
    private $filesize;

    /**
     * @var string
     *
     * @ORM\Column(name="filemime", type="string", length=255)
     */
    private $filemime;
    
    /**
     * @ORM\ManyToOne(
     *          targetEntity="Baazaar\UserBundle\Entity\User",
     *          inversedBy="files")
     */
    private $owner;
    
    
    /**
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(type="datetime")
    */
    private $createdAt;
    
    /**
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;
    
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
     * Set filename
     *
     * @param string $filename
     *
     * @return File
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }


    /**
     * Set filemime
     *
     * @param string $filemime
     *
     * @return File
     */
    public function setFilemime($filemime)
    {
        $this->filemime = $filemime;

        return $this;
    }

    /**
     * Get filemime
     *
     * @return string
     */
    public function getFilemime()
    {
        return $this->filemime;
    }

    /**
     * Set owner
     *
     * @param \Baazaar\UserBundle\Entity\User $owner
     *
     * @return File
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
    
    
    public function getFile() {
        return $this->file;
    }
    
    public function setFile($file) {       
        
        $this->file = $file;
    }

    /**
     * Set filesize
     *
     * @param $filesize
     *
     * @return File
     */
    public function setFilesize( $filesize)
    {
        $this->filesize = $filesize;

        return $this;
    }

    /**
     * Get filesize
     *
     * @return \number
     */
    public function getFilesize()
    {
        return $this->filesize;
    }
    
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
       return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return File
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
