<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participant
 *
 * @ORM\Table(name="participant")
 * @ORM\Entity
 */
class Participant
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
     * @ORM\Column(name="nom", type="string", length=50, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=50, nullable=true)
     */
    private $prenom;

    /**
     * @var integer
     *
     * @ORM\Column(name="idAcademie", type="integer", nullable=true)
     */
    private $idacademie;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=150, nullable=true)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=30, nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="resAdministrative", type="string", length=80, nullable=true)
     */
    private $resadministrative;

    /**
     * @var string
     *
     * @ORM\Column(name="resFamilliale", type="string", length=80, nullable=true)
     */
    private $resfamilliale;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=50, nullable=true)
     */
    private $role;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastUpdate", type="datetime", nullable=true)
     */
    private $lastupdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCrea", type="datetime", nullable=true)
     */
    private $datecrea;



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
     * @return Participant
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
     * Set prenom
     *
     * @param string $prenom
     * @return Participant
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    
        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set idacademie
     *
     * @param integer $idacademie
     * @return Participant
     */
    public function setIdacademie($idacademie)
    {
        $this->idacademie = $idacademie;
    
        return $this;
    }

    /**
     * Get idacademie
     *
     * @return integer 
     */
    public function getIdacademie()
    {
        return $this->idacademie;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Participant
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    
        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Participant
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set resadministrative
     *
     * @param string $resadministrative
     * @return Participant
     */
    public function setResadministrative($resadministrative)
    {
        $this->resadministrative = $resadministrative;
    
        return $this;
    }

    /**
     * Get resadministrative
     *
     * @return string 
     */
    public function getResadministrative()
    {
        return $this->resadministrative;
    }

    /**
     * Set resfamilliale
     *
     * @param string $resfamilliale
     * @return Participant
     */
    public function setResfamilliale($resfamilliale)
    {
        $this->resfamilliale = $resfamilliale;
    
        return $this;
    }

    /**
     * Get resfamilliale
     *
     * @return string 
     */
    public function getResfamilliale()
    {
        return $this->resfamilliale;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Participant
     */
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set lastupdate
     *
     * @param \DateTime $lastupdate
     * @return Participant
     */
    public function setLastupdate($lastupdate)
    {
        $this->lastupdate = $lastupdate;
    
        return $this;
    }

    /**
     * Get lastupdate
     *
     * @return \DateTime 
     */
    public function getLastupdate()
    {
        return $this->lastupdate;
    }

    /**
     * Set datecrea
     *
     * @param \DateTime $datecrea
     * @return Participant
     */
    public function setDatecrea($datecrea)
    {
        $this->datecrea = $datecrea;
    
        return $this;
    }

    /**
     * Get datecrea
     *
     * @return \DateTime 
     */
    public function getDatecrea()
    {
        return $this->datecrea;
    }
}