<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserSeminar
 *
 * @ORM\Table(name="semi_user_seminar")
 * @ORM\Entity
 */
class UserSeminar
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
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *    @ORM\JoinColumn(name="idUser", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Seminar
     *
     * @ORM\ManyToOne(targetEntity="Seminar")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idSeminar", referencedColumnName="id")
     * })
     */
    private $seminar;
    
    /**
     * @var \Status
     *
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idStatus", referencedColumnName="id")
     * })
     */
    private $status;

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
     * @return \Sio\SemiBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    

    /**
     * Set seminar
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
     * Get seminar
     *
     * @return \Sio\SemiBundle\Entity\Seminar
     */
    public function getSeminar()
    {
        return $this->seminar;
    }
    

    /**
     * Set status
     *
     * @param \Sio\SemiBundle\Entity\Status $status
     * @return UserSeminar
     */
    public function setStatus(\Sio\SemiBundle\Entity\Status $status = null)
    {
        $this->status = $status;
   
        return $this;
    }

    /**
     * Get status
     *
     * @return \Sio\SemiBundle\Entity\Status
     */
    public function getStatus()
    {
        return $this->status;
    }
    
}