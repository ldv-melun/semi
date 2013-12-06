<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Academie
 *
 * @ORM\Table(name="academie")
 * @ORM\Entity(repositoryClass="Sio\SemiBundle\Entity\AcademieRepository")
 */
class Academie
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
	* @ORM\OneToMany(targetEntity="Participant", mappedBy="academie", cascade={"remove", "persist"})
	*/
	protected $participants;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;


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
     * @return Academie
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
     * Constructor
     */
    public function __construct()
    {
        $this->participants = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add participants
     *
     * @param \Sio\SemiBundle\Entity\Participant $participants
     * @return Academie
     */
    public function addParticipant(\Sio\SemiBundle\Entity\Participant $participants)
    {
        $this->participants[] = $participants;
    
        return $this;
    }

    /**
     * Remove participants
     *
     * @param \Sio\SemiBundle\Entity\Participant $participants
     */
    public function removeParticipant(\Sio\SemiBundle\Entity\Participant $participants)
    {
        $this->participants->removeElement($participants);
    }

    /**
     * Get participants
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParticipants()
    {
        return $this->participants;
    }
}