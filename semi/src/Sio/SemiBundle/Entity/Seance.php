<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seance
 *
 * @ORM\Table(name="seance")
 * @ORM\Entity
 */
class Seance
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
     * @var integer
     *
     * @ORM\Column(name="idSeminaire", type="integer", nullable=true)
     */
    private $idseminaire;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=150, nullable=true)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="intervenants", type="string", length=300, nullable=true)
     */
    private $intervenants;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbMax", type="integer", nullable=true)
     */
    private $nbmax;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateHeureDebut", type="datetime", nullable=true)
     */
    private $dateheuredebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateHeureFin", type="datetime", nullable=true)
     */
    private $dateheurefin;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=true)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="numRelatif", type="integer", nullable=false)
     */
    private $numrelatif;



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
     * Set idseminaire
     *
     * @param integer $idseminaire
     * @return Seance
     */
    public function setIdseminaire($idseminaire)
    {
        $this->idseminaire = $idseminaire;
    
        return $this;
    }

    /**
     * Get idseminaire
     *
     * @return integer 
     */
    public function getIdseminaire()
    {
        return $this->idseminaire;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Seance
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    
        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set intervenants
     *
     * @param string $intervenants
     * @return Seance
     */
    public function setIntervenants($intervenants)
    {
        $this->intervenants = $intervenants;
    
        return $this;
    }

    /**
     * Get intervenants
     *
     * @return string 
     */
    public function getIntervenants()
    {
        return $this->intervenants;
    }

    /**
     * Set nbmax
     *
     * @param integer $nbmax
     * @return Seance
     */
    public function setNbmax($nbmax)
    {
        $this->nbmax = $nbmax;
    
        return $this;
    }

    /**
     * Get nbmax
     *
     * @return integer 
     */
    public function getNbmax()
    {
        return $this->nbmax;
    }

    /**
     * Set dateheuredebut
     *
     * @param \DateTime $dateheuredebut
     * @return Seance
     */
    public function setDateheuredebut($dateheuredebut)
    {
        $this->dateheuredebut = $dateheuredebut;
    
        return $this;
    }

    /**
     * Get dateheuredebut
     *
     * @return \DateTime 
     */
    public function getDateheuredebut()
    {
        return $this->dateheuredebut;
    }

    /**
     * Set dateheurefin
     *
     * @param \DateTime $dateheurefin
     * @return Seance
     */
    public function setDateheurefin($dateheurefin)
    {
        $this->dateheurefin = $dateheurefin;
    
        return $this;
    }

    /**
     * Get dateheurefin
     *
     * @return \DateTime 
     */
    public function getDateheurefin()
    {
        return $this->dateheurefin;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Seance
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
     * Set numrelatif
     *
     * @param integer $numrelatif
     * @return Seance
     */
    public function setNumrelatif($numrelatif)
    {
        $this->numrelatif = $numrelatif;
    
        return $this;
    }

    /**
     * Get numrelatif
     *
     * @return integer 
     */
    public function getNumrelatif()
    {
        return $this->numrelatif;
    }
}