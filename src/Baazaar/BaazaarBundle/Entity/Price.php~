<?php

namespace Baazaar\BaazaarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Price
 *
 * @ORM\Table(name="price")
 * @ORM\Entity(repositoryClass="Baazaar\BaazaarBundle\Repository\PriceRepository")
 */
class Price
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
     * @var int
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $price_type;

    /**
     * @var decimal
     *
     * @ORM\Column(name="amount", type="decimal")
     */
    private $amount;

    /**
     * @var boolean
     *
     * @ORM\Column(name="accept_bidding", type="boolean")
     */
    private $accept_bidding;

    /**
     * @var decimal
     *
     * @ORM\Column(name="minimum_bidding_amount", type="decimal")
     */
    private $minimum_bidding_amount;




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
     * Set priceType
     *
     * @param integer $priceType
     *
     * @return Price
     */
    public function setPriceType($priceType)
    {
        $this->price_type = $priceType;

        return $this;
    }

    /**
     * Get priceType
     *
     * @return integer
     */
    public function getPriceType()
    {
        return $this->price_type;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Price
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set acceptBidding
     *
     * @param boolean $acceptBidding
     *
     * @return Price
     */
    public function setAcceptBidding($acceptBidding)
    {
        $this->accept_bidding = $acceptBidding;

        return $this;
    }

    /**
     * Get acceptBidding
     *
     * @return boolean
     */
    public function getAcceptBidding()
    {
        return $this->accept_bidding;
    }

    /**
     * Set minimumBiddingAmount
     *
     * @param string $minimumBiddingAmount
     *
     * @return Price
     */
    public function setMinimumBiddingAmount($minimumBiddingAmount)
    {
        $this->minimum_bidding_amount = $minimumBiddingAmount;

        return $this;
    }

    /**
     * Get minimumBiddingAmount
     *
     * @return string
     */
    public function getMinimumBiddingAmount()
    {
        return $this->minimum_bidding_amount;
    }
}
