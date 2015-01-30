<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Meeting
 *
 * @ORM\Table(name="semi_meeting")
 * @ORM\Entity
 */
class Meeting
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
     * @var \Seminar
     *
     * @ORM\ManyToOne(targetEntity="Seminar")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idSeminar", referencedColumnName="id")
     * })
     */
    private $seminar;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="shortDescription", type="string", length=50, nullable=true)
     */
    private $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="stakeholders", type="string", length=300, nullable=true)
     */
    private $stakeholders;

    /**
     * @var integer
     *
     * @ORM\Column(name="maxSeats", type="integer", nullable=true)
     */
    private $maxSeats;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateStart", type="datetime", nullable=true)
     */
    private $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEnd", type="datetime", nullable=true)
     */
    private $dateEnd;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=true)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="relativeNumber", type="integer", nullable=false)
     */
    private $relativeNumber;

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
     * Set description
     *
     * @param string $description
     * @return Meeting
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return Meeting
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    
        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set stakeholders
     *
     * @param string $stakeholders
     * @return Meeting
     */
    public function setStakeholders($stakeholders)
    {
        $this->stakeholders = $stakeholders;
    
        return $this;
    }

    /**
     * Get stakeholders
     *
     * @return string 
     */
    public function getStakeholders()
    {
        return $this->stakeholders;
    }

    /**
     * Set maxSeats
     *
     * @param integer $maxSeats
     * @return Meeting
     */
    public function setMaxSeats($maxSeats)
    {
        $this->maxSeats = $maxSeats;
    
        return $this;
    }

    /**
     * Get maxSeats
     *
     * @return integer 
     */
    public function getMaxSeats()
    {
        return $this->maxSeats;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     * @return Meeting
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
    
        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime 
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     * @return Meeting
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
    
        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime 
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Meeting
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set relativeNumber
     *
     * @param integer $relativeNumber
     * @return Meeting
     */
    public function setRelativeNumber($relativeNumber)
    {
        $this->relativeNumber = $relativeNumber;
    
        return $this;
    }

    /**
     * Get relativeNumber
     *
     * @return integer 
     */
    public function getRelativeNumber()
    {
        return $this->relativeNumber;
    }

    /**
     * Set seminar
     *
     * @param \Sio\SemiBundle\Entity\Seminar $seminar
     * @return Meeting
     */
    public function setSeminar(\Sio\SemiBundle\Entity\Seminar $seminar = null)
    {
        $this->seminar = $seminar;
    
        return $this;
    }

    /**
     * Get idseminar
     *
     * @return \Sio\SemiBundle\Entity\Seminar 
     */
    public function getSeminar()
    {
        return $this->seminar;
    }
}