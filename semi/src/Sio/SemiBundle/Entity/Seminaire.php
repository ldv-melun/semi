<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seminaire
 *
 * @ORM\Table(name="seminaire")
 * @ORM\Entity
 */
class Seminaire
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
     * @ORM\Column(name="nom", type="string", length=150, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="sousTitre", type="string", length=150, nullable=true)
     */
    private $soustitre;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=150, nullable=true)
     */
    private $lieu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="datetime", nullable=true)
     */
    private $datedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime", nullable=true)
     */
    private $datefin;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=300, nullable=true)
     */
    private $commentaire;

    /**
     * @var string
     *
     * @ORM\Column(name="cle", type="string", length=255, nullable=true)
     */
    private $cle;



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
     * Set nom
     *
     * @param string $nom
     * @return Seminaire
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set soustitre
     *
     * @param string $soustitre
     * @return Seminaire
     */
    public function setSoustitre($soustitre)
    {
        $this->soustitre = $soustitre;
    
        return $this;
    }

    /**
     * Get soustitre
     *
     * @return string 
     */
    public function getSoustitre()
    {
        return $this->soustitre;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     * @return Seminaire
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    
        return $this;
    }

    /**
     * Get lieu
     *
     * @return string 
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set datedebut
     *
     * @param \DateTime $datedebut
     * @return Seminaire
     */
    public function setDatedebut($datedebut)
    {
        $this->datedebut = $datedebut;
    
        return $this;
    }

    /**
     * Get datedebut
     *
     * @return \DateTime 
     */
    public function getDatedebut()
    {
        return $this->datedebut;
    }

    /**
     * Set datefin
     *
     * @param \DateTime $datefin
     * @return Seminaire
     */
    public function setDatefin($datefin)
    {
        $this->datefin = $datefin;
    
        return $this;
    }

    /**
     * Get datefin
     *
     * @return \DateTime 
     */
    public function getDatefin()
    {
        return $this->datefin;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Seminaire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    
        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set cle
     *
     * @param string $cle
     * @return Seminaire
     */
    public function setCle($cle)
    {
        $this->cle = $cle;
    
        return $this;
    }

    /**
     * Get cle
     *
     * @return string 
     */
    public function getCle()
    {
        return $this->cle;
    }
}