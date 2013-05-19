<?php
// src/Ace/UserBundle/Entity/User.php

namespace Ace\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
	 * @ORM\Column(type="string", length="255", nullable=true)
	 *
	 * @Assert\MaxLength(limit="255", message="The first name is too long.", groups={"Registration", "Profile"})
	 */
    private $firstname;

    /**
	 * @ORM\Column(type="string", length="255", nullable=true)
	 *
	 * @Assert\MaxLength(limit="255", message="The last name is too long.", groups={"Registration", "Profile"})
	 */
    private $lastname;

    /**
	 * @ORM\Column(type="string", length="255", nullable=true)
     */
    private $twitter;

	/**
	 * @ORM\Column(type="integer", nullable=false, options={"default" = 0})
	 */
	private $karma;

	/**
	 * @ORM\Column(type="integer", nullable=false, options={"default" = 0})
	 */
	private $points;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $referrer_username;

	/**
	 * @ORM\ManyToOne(targetEntity="Ace\UserBundle\Entity\User")
	 * @ORM\JoinColumn(nullable=true)
	 **/
	protected $referrer;

	/**
	 * @ORM\Column(type="string", length="255", nullable=true)
	 */
	private $referral_code;

	/**
	 * @ORM\Column(type="integer", nullable=false, options={"default" = 0})
	 */
	private $referrals;

	/**
	 * @ORM\Column(type="integer", nullable=false, options={"default" = 0})
	 */
	private $walkthrough_status;

	/**
     * Set firstname
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }
	
    /**
     * Set twitter
     *
     * @param string $twitter
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    public function __construct()
    {
        parent::__construct();
        // your own logic
	    $this->setKarma(0);
	    $this->setPoints(0);
	    $this->setReferrals(0);
	    $this->setWalkthroughStatus(0);
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
	 * Set points
	 *
	 * @param integer $points
	 */
	public function setPoints($points)
	{
		$this->points = $points;
	}

	/**
	 * Get points
	 *
	 * @return integer
	 */
	public function getPoints()
	{
		return $this->points;
	}

	/**
     * Set karma
     *
     * @param integer $karma
     */
    public function setKarma($karma)
    {
        $this->karma = $karma;
    }

    /**
     * Get karma
     *
     * @return integer 
     */
    public function getKarma()
    {
        return $this->karma;
    }

	/**
	 * Set referrer_username
	 *
	 * @param string $referrerUsername
	 */
	public function setReferrerUsername($referrerUsername)
	{
		$this->referrer_username = $referrerUsername;
	}

	/**
	 * Get referrer_username
	 *
	 * @return string
	 */
	public function getReferrerUsername()
	{
		return $this->referrer_username;
	}

	/**
	 * Set referrer
	 *
	 * @param Ace\UserBundle\Entity\User $referrer
	 */
	public function setReferrer(\Ace\UserBundle\Entity\User $referrer)
	{
		$this->referrer = $referrer;
	}

	/**
	 * Get referrer
	 *
	 * @return Ace\UserBundle\Entity\User
	 */
	public function getReferrer()
	{
		return $this->referrer;
	}

    /**
     * Set referral_code
     *
     * @param string $referralCode
     */
    public function setReferralCode($referralCode)
    {
        $this->referral_code = $referralCode;
    }

    /**
     * Get referral_code
     *
     * @return string 
     */
    public function getReferralCode()
    {
        return $this->referral_code;
    }


    /**
     * Set referrals
     *
     * @param integer $referrals
     */
    public function setReferrals($referrals)
    {
        $this->referrals = $referrals;
    }

    /**
     * Get referrals
     *
     * @return integer 
     */
    public function getReferrals()
    {
        return $this->referrals;
    }


    /**
     * Set walkthrough_status
     *
     * @param integer $walkthroughStatus
     */
    public function setWalkthroughStatus($walkthroughStatus)
    {
        $this->walkthrough_status = $walkthroughStatus;
    }

    /**
     * Get walkthrough_status
     *
     * @return integer 
     */
    public function getWalkthroughStatus()
    {
        return $this->walkthrough_status;
    }
}