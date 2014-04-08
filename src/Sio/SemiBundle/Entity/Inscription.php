<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inscription
 *
 * @ORM\Table(name="inscription")
 * @ORM\Entity(repositoryClass="Sio\SemiBundle\Entity\InscriptionRepository")
 */
class Inscription
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Participant", inversedBy="seances", cascade={"remove"})
     * @ORM\JoinColumn(name="idParticipant", referencedColumnName="id")
     * 
     */
    private $participant;
	/**
     * @var integer
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Seance", inversedBy="participants", cascade={"remove"})
     * @ORM\JoinColumn(name="idSeance", referencedColumnName="id")
     */
    private $seance;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateInscr", type="datetime")
     */
    private $dateInscr;


    

    /**
     * Set idParticipant
     *
     * @param integer $idParticipant
     * @return Inscription
     */
    public function setIdParticipant($idParticipant)
    {
        $this->idParticipant = $idParticipant;
    
        return $this;
    }

    /**
     * Get idParticipant
     *
     * @return integer 
     */
    public function getIdParticipant()
    {
        return $this->idParticipant;
    }

    /**
     * Set idSeance
     *
     * @param integer $idSeance
     * @return Inscription
     */
    public function setIdSeance($idSeance)
    {
        $this->idSeance = $idSeance;
    
        return $this;
    }

    /**
     * Get idSeance
     *
     * @return integer 
     */
    public function getIdSeance()
    {
        return $this->idSeance;
    }

    /**
     * Set dateInscr
     *
     * @param \DateTime $dateInscr
     * @return Inscription
     */
    public function setDateInscr($dateInscr)
    {
        $this->dateInscr = $dateInscr;
    
        return $this;
    }

    /**
     * Get dateInscr
     *
     * @return \DateTime 
     */
    public function getDateInscr()
    {
        return $this->dateInscr;
    }

    /**
     * Set participant
     *
     * @param \Sio\SemiBundle\Entity\Participant $participant
     * @return Inscription
     */
    public function setParticipant(\Sio\SemiBundle\Entity\Participant $participant)
    {
        $this->participant = $participant;
    
        return $this;
    }

    /**
     * Get participant
     *
     * @return \Sio\SemiBundle\Entity\Participant 
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * Set seance
     *
     * @param \Sio\SemiBundle\Entity\Seminaire $seance
     * @return Inscription
     */
    public function setSeance(\Sio\SemiBundle\Entity\Seminaire $seance)
    {
        $this->seance = $seance;
    
        return $this;
    }

    /**
     * Get seance
     *
     * @return \Sio\SemiBundle\Entity\Seminaire 
     */
    public function getSeance()
    {
        return $this->seance;
    }
}