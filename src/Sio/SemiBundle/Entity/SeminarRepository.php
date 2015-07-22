<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class SeminarRepository extends EntityRepository {
  
/**
 * 
 * @param Seminar $seminar
 * @return array of status: key=id, value=name
 */
 public function getAllUserStatusBySeminar($seminar) {
    // TODO trop de requêtes ici, il y a moyen de mieux faire avec Join...
    $em =  $this->getEntityManager();
    $seminarStatus = $em->getRepository("SioSemiBundle:SeminarStatus")
        ->findBy(array("seminar" => $seminar));
    $allStatus = array();
    foreach ($seminarStatus as $semiStatus) :
      $statusObject = $semiStatus->getStatus();
      //$allStatus[$statusObject->getId()]['name'] = $statusObject->getName(); 
      $allStatus[$statusObject->getId()] = $statusObject->getName(); 
    endforeach;
    //$allStatus[2]['checked'] = TRUE; 
    return $allStatus;
  }
  
  
  /*
   *  $sql = "SELECT DISTINCT participant.id, participant.nom, participant.prenom, participant.mail, participant.titre, academie.nom acad FROM participant, inscription, academie, seance WHERE participant.id = inscription.idParticipant AND inscription.idSeance=seance.id AND academie.id = participant.idAcademie AND seance.idSeminaire = :IDSE ORDER BY academie.nom ASC, participant.nom ASC";

   */
  
  
   public function getAllRegistrationsUserSeminar($seminar) {
    $em = $this->getEntityManager();
    $sql = "SELECT DISTINCT semi_user.id, "
        . "semi_organisation.name as orga, "
        . "semi_user.lastName, "
        . "semi_user.firstName "
//        . "semi_user.email "  
        . "FROM semi_user, "
        . "semi_registration, "
        . "semi_organisation, "
        . "semi_meeting "
        . "WHERE semi_user.id = semi_registration.idUser "
        . "AND semi_registration.idMeeting = semi_meeting.id "
        . "AND semi_organisation.id = semi_user.idOrganisation "
        . "AND semi_meeting.idSeminar = ? "
        . "ORDER BY semi_organisation.name ASC, semi_user.lastName ASC";

    $rsm = new ResultSetMapping;
    $rsm->addScalarResult('id', 'id');
    $rsm->addScalarResult('firstName', 'firstName');
    $rsm->addScalarResult('lastName', 'lastName');
    $rsm->addScalarResult('email', 'email');
    //$rsm->addJoinedEntityResult('Sio\SemiBundle\Entity\Organisation' , 'o', 'u', 'organisation');
    $rsm->addScalarResult('orga', 'orga');
    
    // http://guidella.free.fr/General/symfony2NativeSQL.html
    
    $query = $em->createNativeQuery($sql, $rsm);
    $query->setParameter(1, $seminar->getId());

    // native sql
    /*
    $query = $em->createQuery(
		"SELECT r FROM SioSemiBundle:Registration r "
		. "JOIN r.user user "
    . "JOIN r.meeting m "
    . "WHERE m.seminar = :sem "    
		. "ORDER BY user.organisation ASC"
        );
    
    $query->setParameter(':sem', $seminar);
     
     */
    $res = $query->getResult();
    $header = array("ID", "ACADEMIE", "NOM", "PRENOM" /*, "EMAIL",*/ );
    array_unshift($res, $header );
    return $res;
    }
}
