<?php

namespace Baazaar\BaazaarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdReport
 *
 * @ORM\Table(name="ad_report")
 * @ORM\Entity(repositoryClass="Baazaar\BaazaarBundle\Repository\AdReportRepository")
 */
class AdReport
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
     * @ORM\ManyToOne(
     *          targetEntity="Baazaar\BaazaarBundle\Entity\Ad",
     *          inversedBy="ad_reports")
     */
    private $ad;

    /**
     * @ORM\ManyToOne(
     *          targetEntity="Baazaar\UserBundle\Entity\User",
     *          inversedBy="ad_reports")
     */
    private $reporter;

    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="string", length=255)
     */
    private $reason;


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
     * Set reason
     *
     * @param string $reason
     *
     * @return AdReport
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set ad
     *
     * @param \Baazaar\BaazaarBundle\Entity\Ad $ad
     *
     * @return AdReport
     */
    public function setAd(\Baazaar\BaazaarBundle\Entity\Ad $ad = null)
    {
        $this->ad = $ad;

        return $this;
    }

    /**
     * Get ad
     *
     * @return \Baazaar\BaazaarBundle\Entity\Ad
     */
    public function getAd()
    {
        return $this->ad;
    }

    /**
     * Set reporter
     *
     * @param \Baazaar\UserBundle\Entity\User $reporter
     *
     * @return AdReport
     */
    public function setReporter(\Baazaar\UserBundle\Entity\User $reporter = null)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return \Baazaar\UserBundle\Entity\User
     */
    public function getReporter()
    {
        return $this->reporter;
    }
}
