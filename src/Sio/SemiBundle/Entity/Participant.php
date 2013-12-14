<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Participant
 *
 * @ORM\Table(name="participant")
 * @ORM\Entity(repositoryClass="Sio\SemiBundle\Entity\ParticipantRepository")
 */
class Participant implements UserInterface, \Serializable
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=50)
     */
    private $prenom;
	
	/**
	* @ORM\ManyToOne(targetEntity="Academie", inversedBy="participants", cascade={"persist"})
	* @ORM\JoinColumn(name="idAcademie", referencedColumnName="id")
	*/
	protected $academie;
	/**
	* @ORM\OneToMany(targetEntity="Inscription", mappedBy="participant", cascade={"remove", "persist"})
	*/
	protected $seance;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=150, unique=true)
     */
    protected $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=30)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="resAdministrative", type="string", length=80)
     */
    private $resAdministrative;

    /**
     * @var string
     *
     * @ORM\Column(name="resFamilliale", type="string", length=80)
     */
    private $resFamiliale;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    protected $roles;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastUpdate", type="datetime")
     */
    private $lastUpdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCrea", type="datetime")
     */
    private $dateCrea;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;
    
    /**
	 * @var string
	 *
     * @ORM\Column(name="username", type="string", length=255)
     */
    protected $username;
    
    /**
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

	
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
     * Set resAdministrative
     *
     * @param string $resAdministrative
     * @return Participant
     */
    public function setResAdministrative($resAdministrative)
    {
        $this->resAdministrative = $resAdministrative;
    
        return $this;
    }

    /**
     * Get resAdministrative
     *
     * @return string 
     */
    public function getResAdministrative()
    {
        return $this->resAdministrative;
    }

    /**
     * Set resFamiliale
     *
     * @param string $resFamiliale
     * @return Participant
     */
    public function setResFamiliale($resFamiliale)
    {
        $this->resFamiliale = $resFamiliale;
    
        return $this;
    }

    /**
     * Get resFamiliale
     *
     * @return string 
     */
    public function getResFamiliale()
    {
        return $this->resFamiliale;
    }

    

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     * @return Participant
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
    
        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime 
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * Set dateCrea
     *
     * @param \DateTime $dateCrea
     * @return Participant
     */
    public function setDateCrea($dateCrea)
    {
        $this->dateCrea = $dateCrea;
    
        return $this;
    }

    /**
     * Get dateCrea
     *
     * @return \DateTime 
     */
    public function getDateCrea()
    {
        return $this->dateCrea;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Participant
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seance = new \Doctrine\Common\Collections\ArrayCollection();
        //$this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = array();
    }
    
    /**
     * Set academie
     *
     * @param \Sio\SemiBundle\Entity\Academie $academie
     * @return Participant
     */
    public function setAcademie(\Sio\SemiBundle\Entity\Academie $academie = null)
    {
        $this->academie = $academie;
    
        return $this;
    }

    /**
     * Get academie
     *
     * @return \Sio\SemiBundle\Entity\Academie 
     */
    public function getAcademie()
    {
        return $this->academie;
    }

    /**
     * Add seance
     *
     * @param \Sio\SemiBundle\Entity\Inscription $seance
     * @return Participant
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

    public function eraseCredentials() {
        
    }

    public function getRoles() {
        return $this->roles;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getUsername() {
        return $this->username;
    }

    /**
     * Set roles
     *
     * @param $roles
     * @return Participant
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    
        return $this;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Participant
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Participant
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }
    public function serialize() {
            return serialize(array($this->getId(),$this->getMail()));
    }



    public function unserialize($serialized) {
            $arr = unserialize($serialized);
            $this->id = $arr[0];
            $this->mail = $arr[1];
            $this->username = $arr[1];
    }
     
}