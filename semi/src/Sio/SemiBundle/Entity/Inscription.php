<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inscription
 *
 * @ORM\Table(name="inscription")
 * @ORM\Entity
 */
class Inscription
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idParticipant", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idparticipant;

    /**
     * @var integer
     *
     * @ORM\Column(name="idSeance", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idseance;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateInscr", type="datetime", nullable=true)
     */
    private $dateinscr;



    /**
     * Set idparticipant
     *
     * @param integer $idparticipant
     * @return Inscription
     */
    public function setIdparticipant($idparticipant)
    {
        $this->idparticipant = $idparticipant;
    
        return $this;
    }

    /**
     * Get idparticipant
     *
     * @return integer 
     */
    public function getIdparticipant()
    {
        return $this->idparticipant;
    }

    /**
     * Set idseance
     *
     * @param integer $idseance
     * @return Inscription
     */
    public function setIdseance($idseance)
    {
        $this->idseance = $idseance;
    
        return $this;
    }

    /**
     * Get idseance
     *
     * @return integer 
     */
    public function getIdseance()
    {
        return $this->idseance;
    }

    /**
     * Set dateinscr
     *
     * @param \DateTime $dateinscr
     * @return Inscription
     */
    public function setDateinscr($dateinscr)
    {
        $this->dateinscr = $dateinscr;
    
        return $this;
    }

    /**
     * Get dateinscr
     *
     * @return \DateTime 
     */
    public function getDateinscr()
    {
        return $this->dateinscr;
    }
}