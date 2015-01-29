<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserSeminar
 *
 * @ORM\Table(name="userSeminar")
 * @ORM\Entity
 */
class userSeminar
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
     * @var \user
     *
     * @ORM\ManyToOne(targetEntity="user")
     * @ORM\JoinColumns({
     *@ORM\JoinColumn(name="idUser", referencedColumnName="id")
     */
    private $idUser;

    /**
     * @var \seminar
     *
     * @ORM\ManyToOne(targetEntity="seminar")
     * @ORM\JoinColumns({
     *@ORM\JoinColumn(name="idSeminar", referencedColumnName="id")
     */
    private $idSeminar;
    
    /**
     * @var \status
     *
     * @ORM\ManyToOne(targetEntity="status")
     * @ORM\JoinColumns({
     *@ORM\JoinColumn(name="idStatus", referencedColumnName="id")
     */
    private $idStatus;

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
     * Set user
     *
     * @param \Sio\SemiBundle\Entity\User $user
     * @return UserSeminar
     */
    public function setUser(\Sio\SemiBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Sio\SemiBundle\Entity\user 
     */
    public function getUser()
    {
        return $this->user;
    }
    

    /**
     * Set Seminar
     *
     * @param \Sio\SemiBundle\Entity\Seminar $seminar
     * @return UserSeminar
     */
    public function setSeminar(\Sio\SemiBundle\Entity\Seminar $seminar = null)
    {
        $this->seminar = $seminar;
   
        return $this;
    }

    /**
     * Get Seminar
     *
     * @return \Sio\SemiBundle\Entity\Seminar
     */
    public function getSeminar()
    {
        return $this->seminar;
    }
    

    /**
     * Set Status
     *
     * @param \Sio\SemiBundle\Entity\Status $status
     * @return UserSeminar
     */
    public function setStatus(\Sio\SemiBundle\Entity\Status $status = null)
    {
        $this->Status = $status;
   
        return $this;
    }

    /**
     * Get Status
     *
     * @return \Sio\SemiBundle\Entity\Status
     */
    public function getStatus()
    {
        return $this->status;
    }
    
}