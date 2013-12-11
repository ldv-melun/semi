<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * seance
 *
 * @ORM\Table(name="seance")
 * @ORM\Entity(repositoryClass="Sio\SemiBundle\Entity\SeanceRepository")
 */
class Seance
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
	* @ORM\ManyToOne(targetEntity="Seminaire", inversedBy="seances", cascade={"remove"})
	* @ORM\JoinColumn(name="idSeminaire", referencedColumnName="id")
	*/
	protected $seminaire;
	
	/**
	* @ORM\OneToMany(targetEntity="Inscription", mappedBy="seance", cascade={"remove", "persist"})
	*/
	protected $participant;
	
    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=150)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="intervenants", type="string", length=300)
     */
    private $intervenants;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbMax", type="integer")
     */
    private $nbMax;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateHeureDebut", type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateHeureFin", type="datetime")
     */
    private $dateHeureFin;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="numRelatif", type="integer")
     */
    private $numRelatif;


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
     * Set libelle
     *
     * @param string $libelle
     * @return seance
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
     * @return seance
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
     * Set nbMax
     *
     * @param integer $nbMax
     * @return seance
     */
    public function setNbMax($nbMax)
    {
        $this->nbMax = $nbMax;
    
        return $this;
    }

    /**
     * Get nbMax
     *
     * @return integer 
     */
    public function getNbMax()
    {
        return $this->nbMax;
    }

    /**
     * Set dateHeureDebut
     *
     * @param \DateTime $dateHeureDebut
     * @return seance
     */
    public function setDateHeureDebut($dateHeureDebut)
    {
        $this->dateHeureDebut = $dateHeureDebut;
    
        return $this;
    }

    /**
     * Get dateHeureDebut
     *
     * @return \DateTime 
     */
    public function getDateHeureDebut()
    {
        return $this->dateHeureDebut;
    }

    /**
     * Set dateHeureFin
     *
     * @param \DateTime $dateHeureFin
     * @return seance
     */
    public function setDateHeureFin($dateHeureFin)
    {
        $this->dateHeureFin = $dateHeureFin;
    
        return $this;
    }

    /**
     * Get dateHeureFin
     *
     * @return \DateTime 
     */
    public function getDateHeureFin()
    {
        return $this->dateHeureFin;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return seance
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
     * Set numRelatif
     *
     * @param integer $numRelatif
     * @return seance
     */
    public function setNumRelatif($numRelatif)
    {
        $this->numRelatif = $numRelatif;
    
        return $this;
    }

    /**
     * Get numRelatif
     *
     * @return integer 
     */
    public function getNumRelatif()
    {
        return $this->numRelatif;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seance = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set seminaire
     *
     * @param \Sio\SemiBundle\Entity\Seminaire $seminaire
     * @return Seance
     */
    public function setSeminaire(\Sio\SemiBundle\Entity\Seminaire $seminaire = null)
    {
        $this->seminaire = $seminaire;
    
        return $this;
    }

    /**
     * Get seminaire
     *
     * @return \Sio\SemiBundle\Entity\Seminaire 
     */
    public function getSeminaire()
    {
        return $this->seminaire;
    }

    /**
     * Add seance
     *
     * @param \Sio\SemiBundle\Entity\Inscription $seance
     * @return Seance
     */
    public function addSeance(\Sio\SemiBundle\Entity\Inscription $seance)
    {
        $this->seance[] = $seance;
    
        return $this;
    }

    /**
     * Remove seance
     *
     * @param \Sio\SemiBundle\Entity\Inscription $seance
     */
    public function removeSeance(\Sio\SemiBundle\Entity\Inscription $seance)
    {
        $this->seance->removeElement($seance);
    }

    /**
     * Get seance
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeance()
    {
        return $this->seance;
    }
}