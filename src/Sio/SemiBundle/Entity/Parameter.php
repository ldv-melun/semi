<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parameter
 *
 * @ORM\Table(name="semi_parameter")
 * @ORM\Entity
 */
class Parameter
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
     * @ORM\Column(name="clef", type="string", length=150, nullable=true)
     */
    private $clef;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=150, nullable=true)
     */
    private $value;



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
     * Set clef
     *
     * @param string $clef
     * @return Parameter
     */
    public function setClef($clef)
    {
        $this->clef = $clef;
    
        return $this;
    }

    /**
     * Get clef
     *
     * @return string 
     */
    public function getClef()
    {
        return $this->clef;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Parameter
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
}