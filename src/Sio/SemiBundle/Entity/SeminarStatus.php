<?php

/**
 * Seminar
 *
 * @ORM\Table(name="semi_seminarStatus")
 * @ORM\Entity
 */
class SeminarStatus
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
     * Set seminar
     *
     * @param \Sio\SemiBundle\Entity\Seminar $seminar
     * @return SeminarStatus
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
     * @return SeminarStatus
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
