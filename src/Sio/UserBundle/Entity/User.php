<?php

namespace Sio\UserBundle\Entity;

use Sio\SemiBundle\Entity\Organisation as Organisation;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="semi_user")
 * @ORM\Entity(repositoryClass="Sio\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="Veuillez entrer votre nom", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min="3",
     *     max="50",
     *     minMessage="Votre nom est trop court",
     *     maxMessage="Votre nom est trop long",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=50, nullable=true)
     * 
     * @Assert\NotBlank(message="Veuillez entrer votre prénom", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min="2",
     *     max="50",
     *     minMessage="Votre prénom est trop court",
     *     maxMessage="Votre prénom est trop long",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="homeCity", type="string", length=80, nullable=true)
     * @Assert\NotBlank(message="Veuillez entrer la ville de votre résidence administrative", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min="2",
     *     max="80",
     *     minMessage="La ville de votre résidence administrative est trop court",
     *     maxMessage="La ville de votre résidence administrative est trop long",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $homeCity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastUpdate", type="datetime", nullable=true)
     */
    private $lastUpdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCrea", type="datetime", nullable=true)
     */
    private $dateCrea;

    /**
     * @var \Organisation
     *
     * @ORM\ManyToOne(targetEntity="Sio\SemiBundle\Entity\Organisation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idOrganisation", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="Veuillez choisir votre choix", groups={"Registration", "Profile"})
     */
    private $organisation;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ipLastLogin", type="string", length=75, nullable=true)
     */
    private $ipLastLogin;
    

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
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }


    /**
     * Set jobCity
     *
     * @param string $jobCity
     * @return User
     */
    public function setJobCity($jobCity)
    {
        $this->jobCity = $jobCity;
    
        return $this;
    }

    /**
     * Get jobCity
     *
     * @return string 
     */
    public function getJobCity()
    {
        return $this->jobCity;
    }

    /**
     * Set homeCity
     *
     * @param string $homeCity
     * @return User
     */
    public function setHomeCity($homeCity)
    {
        $this->homeCity = $homeCity;
    
        return $this;
    }

    /**
     * Get homeCity
     *
     * @return string 
     */
    public function getHomeCity()
    {
        return $this->homeCity;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     * @return User
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
    
        return $this;
    }

    /**
     * Get lastupdate
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
     * @return User
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
     * Set organisation
     *
     * @param \Sio\SemiBundle\Entity\Organisation $organisation
     * @return User
     */
    public function setOrganisation(\Sio\SemiBundle\Entity\Organisation $organisation = null)
    {
        $this->organisation = $organisation;
    
        return $this;
    }

    /**
     * Get organisation
     *
     * @return \Sio\SemiBundle\Entity\Organisation 
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }
    
    /**
     * Set dateLastLogin
     *
     * @param \DateTime $dateLastLogin
     * @return User
     */
    public function setDateLastLogin($dateLastLogin)
    {
        $this->dateLastLogin = $dateLastLogin;
    
        return $this;
    }

    /**
     * Get dateLastLogin
     *
     * @return \DateTime 
     */
    public function getDateLastLogin()
    {
        return $this->dateLastLogin;
    }
    
    /**
     * Set ipLastLogin
     *
     * @param string $ipLastLogin
     * @return User
     */
    public function setIpLastLogin($ipLastLogin)
    {
        $this->ipLastLogin = $ipLastLogin;
    
        return $this;
    }

    /**
     * Get ipLastLogin
     *
     * @return string 
     */
    public function getIpLastLogin()
    {
        return $this->ipLastLogin;
    }
    
    public function __construct()
    {
      parent::__construct();
      $this->dateCrea = new \DateTime();
    }

}