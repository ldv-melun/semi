<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Registration
 *
 * @ORM\Table(name="semi_registration")
 * @ORM\Entity
 */
class Registration
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="Sio\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Meeting
     *
     * @ORM\ManyToOne(targetEntity="Meeting")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idMeeting", referencedColumnName="id")
     * })
     */
    private $meeting;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateRegistration", type="datetime", nullable=true)
     */
    private $dateRegistration;

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
     * Set dateRegistration
     *
     * @param \DateTime $dateRegistration
     * @return Registration
     */
    public function setDateRegistration($dateRegistration)
    {
        $this->dateRegistration = $dateRegistration;
    
        return $this;
    }

    /**
     * Get dateRegistration
     *
     * @return \DateTime 
     */
    public function getDateRegistration()
    {
        return $this->dateRegistration;
    }

    /**
     * Set user
     *
     * @param \Sio\UserBundle\Entity\User $user
     * @return Registration
     */
    public function setUser(\Sio\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Sio\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set meeting
     *
     * @param \Sio\SemiBundle\Entity\Meeting $meeting
     * @return Registration
     */
    public function setMeeting(\Sio\SemiBundle\Entity\Meeting $meeting = null)
    {
        $this->meeting = $meeting;
    
        return $this;
    }

    /**
     * Get meeting
     *
     * @return \Sio\SemiBundle\Entity\Meeting 
     */
    public function getMeeting()
    {
        return $this->meeting;
    }
}