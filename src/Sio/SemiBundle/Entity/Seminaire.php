<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seminaire
 *
 * @ORM\Table(name="seminaire")
 * @ORM\Entity(repositoryClass="Sio\SemiBundle\Entity\SeminaireRepository")
 */
class Seminaire
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
	* @ORM\OneToMany(targetEntity="Seance", mappedBy="seminaire", cascade={"remove", "persist"})
	*/
	protected $seances;


    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=150)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="sousTitre", type="string", length=150)
     */
    private $sousTitre;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=150)
     */
    private $lieu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="datetime")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime")
     */
    private $dateFin;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=300)
     */
    private $commentaire;

    /**
     * @var string
     *
     * @ORM\Column(name="cle", type="string", length=255)
     */
    private $cle;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="inscription_actif", type="integer", length=1)
     */
    private $inscriptionActif;


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
     * Set sousTitre
     *
     * @param string $sousTitre
     * @return Seminaire
     */
    public function setSousTitre($sousTitre)
    {
        $this->sousTitre = $sousTitre;
    
        return $this;
    }

    /**
     * Get sousTitre
     *
     * @return string 
     */
    public function getSousTitre()
    {
        return $this->sousTitre;
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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Seminaire
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    
        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Seminaire
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    
        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->dateFin;
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seances = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add seances
     *
     * @param \Sio\SemiBundle\Entity\Seance $seances
     * @return Seminaire
     */
    public function addSeance(\Sio\SemiBundle\Entity\Seance $seances)
    {
        $this->seances[] = $seances;
    
        return $this;
    }

    /**
     * Remove seances
     *
     * @param \Sio\SemiBundle\Entity\Seance $seances
     */
    public function removeSeance(\Sio\SemiBundle\Entity\Seance $seances)
    {
        $this->seances->removeElement($seances);
    }

    /**
     * Get seances
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeances()
    {
        return $this->seances;
    }

    /**
     * Set inscriptionActif
     *
     * @param integer $inscriptionActif
     * @return Seminaire
     */
    public function setInscriptionActif($inscriptionActif)
    {
        $this->inscriptionActif = $inscriptionActif;
    
        return $this;
    }

    /**
     * Get inscriptionActif
     *
     * @return integer 
     */
    public function getInscriptionActif()
    {
        return $this->inscriptionActif;
    }
}