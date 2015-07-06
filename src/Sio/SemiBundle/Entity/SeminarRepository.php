<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SeminarRepository extends EntityRepository {

   public function getAllStatusBySeminar($seminar) {
    // TODO il y a moyen de mieux faire...
    $em =  $this->getEntityManager();
    $seminarStatus = $em->getRepository("SioSemiBundle:SeminarStatus")
        ->findBy(array("seminar" => $seminar));
    $status = array();
    foreach ($seminarStatus as $object) {
      $status[] = $em->getRepository("SioSemiBundle:Status")
          ->findBy(array("id" => $object->getStatus()));
    }
    return $status;
  }
  
}
