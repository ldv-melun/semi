<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * For header for view/tables
 *
 * @author kpu
 */
namespace Sio\SemiBundle\Util;

class Header {
  //put your code here
  private $name;
  private $type;
  
  public function __construct($name, $type){
    $this->setName($name);
    $this->setType($type);
  }
  
  public function getName() {    
    return $this->name;
  }
  
  public function setName($name) {    
    $this->name = $name;
  }
  
  public function getType() {
    return $this->type;
  }
 
  public function setType($type) {
    $this->type = $type;
  }
}
