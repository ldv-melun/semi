<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seminar
 * 
 * @ORM\Entity(repositoryClass="Sio\SemiBundle\Entity\SeminarRepository") 
 * @ORM\Table(name="semi_seminar")
 */
class Seminar
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=150, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=150, nullable=true)
     */
    private $location;

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
     * @var \DateTime
     *
     * @ORM\Column(name="beginRegistering", type="datetime", nullable=true)
     */
    private $beginRegistering;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endRegistering", type="datetime", nullable=true)
     */
    private $endRegistering;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=300, nullable=true)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="clef", type="string", length=255, nullable=true)
     */
    private $clef;

    /**
     * @var \State
     *
     * @ORM\ManyToOne(targetEntity="State")
     * @ORM\JoinColumns({
     *    @ORM\JoinColumn(name="idState", referencedColumnName="id")
     * })
     */
    private $state;



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
     * Set name
     *
     * @param string $name
     * @return Seminar
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
     * Set description
     *
     * @param string $description
     * @return Seminar
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
     * Set location
     *
     * @param string $location
     * @return Seminar
     */
    public function setLocation($location)
    {
        $this->location = $location;
    
        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     * @return Seminar
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
     * @return Seminar
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
     * Set beginRegistering
     *
     * @param \DateTime $beginRegistering
     * @return Seminar
     */
    public function setBeginRegistering($beginRegistering)
    {
        $this->beginRegistering = $beginRegistering;
    
        return $this;
    }

    /**
     * Get beginRegistering
     *
     * @return \DateTime 
     */
    public function getBeginRegistering()
    {
        return $this->beginRegistering;
    }
    
    /**
     * Set endRegistering
     *
     * @param \DateTime $endRegistering
     * @return Seminar
     */
    public function setEndRegistering($endRegistering)
    {
        $this->endRegistering = $endRegistering;
    
        return $this;
    }

    /**
     * Get endRegistering
     *
     * @return \DateTime 
     */
    public function getEndRegistering()
    {
        return $this->endRegistering;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Seminar
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set clef
     *
     * @param string $clef
     * @return Seminar
     */
    public function setClef($clef)
    {
        $this->clef = $clef;
    
        return $this;
    }

    /**
     * Get clef
     *
     * @return string 
     */
    public function getClef()
    {
        return $this->clef;
    }

    /**
     * Set state
     *
     * @param \Sio\SemiBundle\Entity\State $state
     * @return Seminar
     */
    public function setState(\Sio\SemiBundle\Entity\State $state = null)
    {
        $this->state = $state;
   
        return $this;
    }
    
    /**
     * Get state
     *
     * @return \Sio\SemiBundle\Entity\State
     */
    public function getState()
    {
        return $this->state;
    }
    
    
}