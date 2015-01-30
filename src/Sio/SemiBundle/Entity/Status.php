<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Status
 *
 * @ORM\Table(name="semi_status")
 * @ORM\Entity
 */
class Status {
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var \seminar
     *
     * @ORM\ManyToOne(targetEntity="Seminar")
     * @ORM\JoinColumns({
     *    @ORM\JoinColumn(name="idSeminar", referencedColumnName="id")
     * })
     */
    private $seminar;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50, nullable=true)
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
     * Set seminar
     *
     * @param \Sio\SemiBundle\Entity\Seminar $seminar
     * @return Status
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
     * @param string $status
     * @return Status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
