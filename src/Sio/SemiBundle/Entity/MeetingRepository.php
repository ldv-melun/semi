<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class MeetingRepository extends EntityRepository {

  /**
   * Used to count the number of seats taken.
   * @param Meeting $meeting
   * @return int
   */
  public function countSeatsTaken($meeting) {
    return $this->getEntityManager()
            ->createQuery(
                'SELECT count(r.id) FROM Sio\SemiBundle\Entity\Registration r WHERE r.meeting = :meeting'
            )
            ->setParameter('meeting', $meeting)
            ->getSingleScalarResult();
  }

  public function countNbMeetingRegister($user, $seminar) {
    return $this->getEntityManager()
            ->createQuery(
                'SELECT count(r.id) FROM Sio\SemiBundle\Entity\Registration r, Sio\SemiBundle\Entity\Meeting m WHERE r.meeting = m.id AND m.seminar = :seminar AND r.user = :user'
            )
            ->setParameter('user', $user)
            ->setParameter('seminar', $seminar)
            ->getSingleScalarResult();
  }

  /**
   * Used to DELETE a Registering Object using \DateTime
   * (Maybe we should place this function in RegistrationRepository ?)
   * @param \DateTime $dateHeureDebut
   * @param Sio\UserBundle\Entity\User $user
   * @param Seminar $seminar
   */
  public function razInscriptionSeances($dateHeureDebut, $user, $seminar) {
    $this->getEntityManager()
        ->createQuery(
            'DELETE FROM Sio\SemiBundle\Entity\Registration r WHERE r.user = :user AND r.meeting IN (SELECT m.id FROM Sio\SemiBundle\Entity\Meeting m WHERE m.dateStart = :dhd AND m.seminar = :seminar)'
        )
        ->setParameter(':dhd', $dateHeureDebut)
        ->setParameter(':user', $user->getId())
        ->setParameter(':seminar', $seminar->getId())
        ->execute();
    return $this->countNbMeetingRegister($user, $seminar);
  }

  /**
   *
   * Obtient des informations (nombre d'inscrits...) sur les seances d'un même créneau (dateStart)
   * TODO : do it in OQL
   * @param seminar $seminar 
   * @param date_sql $dateStart 
   */
  public function getStatInscriptionSeance($seminar, $dateStart) {
    $rsm = new ResultSetMapping;
    $rsm->addScalarResult('id', 'id');
    $rsm->addScalarResult('free', 'free');
    $rsm->addScalarResult('maxSeats', 'maxSeats');

    // $sql = 'SELECT semi_meeting.id, maxSeats-count(idMeeting) AS free, maxSeats FROM semi_meeting LEFT JOIN semi_registration ON semi_meeting.id=idMeeting WHERE idSeminar = ? AND dateStart = ? GROUP BY semi_meeting.id';
    //
    //or select ALL stats for seminar of this meeting
    $sql = 'SELECT semi_meeting.id, maxSeats-count(idMeeting) AS free, maxSeats FROM semi_meeting LEFT JOIN semi_registration ON semi_meeting.id=idMeeting WHERE idSeminar = ? GROUP BY semi_meeting.id';
    
    $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

    $query->setParameter(1, $seminar->getId());
   // $query->setParameter(2, $dateStart);
    
    return $query->getResult();
  }

   /**
   *
   * Obtient des informations (nombre d'inscrits...) sur les seances d'un seminaire
   * pour être utilisé via une requête ajax des clients (gestionnaire only ?) afin de visualiserr
   * en temps réel les prise de décision des utilisateurs
   * TODO : do it in OQL
   * @param seminar
   */
  public function getStatInscriptionsSeancesBySeminar($seminar) {
    $rsm = new ResultSetMapping;
    $rsm->addScalarResult('id', 'id');
    $rsm->addScalarResult('free', 'free');
    $rsm->addScalarResult('maxSeats', 'maxSeats');

    // select ALL stats for seminar of this meeting
    $sql = 'SELECT semi_meeting.id, maxSeats-count(idMeeting) AS free, maxSeats FROM semi_meeting LEFT JOIN semi_registration ON semi_meeting.id=idMeeting WHERE idSeminar = ? GROUP BY semi_meeting.id';
    $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
    $query->setParameter(1, $seminar->getId());

    return $query->getResult();
  }

  /**
   * 
   * @param type $seminar
   * @return array of meetings with only one atelier representant by atelier group 
   */
  public function getOnlyMeetingsWithDistinctsPlagesHoraires($seminar) {

     // astuce qui fonctionne si tout meeting a par 
     // défaut son relativenumber à 1 
     // ATTENTION : les ateliers doivent commencer par 1, pour chaque groupe
    
    
     $q = $this->createQueryBuilder('m')
       ->where('m.seminar = :sem')
       ->andWhere('m.relativeNumber = 1')      
       ->setParameter("sem", $seminar)
       ->orderBy('m.dateStart');
         
     return $q
          ->getQuery()
          ->getResult();
  }
  
  /**
   * 
   * @param type $seminar
   * @return one or none meeting registred by this user at date this meeting
   */
  public function getMeetingUser($seminar, $user, $meeting) {
     $em = $this->getEntityManager();
     $q = $em->createQuery(
      "SELECT r, m FROM SioSemiBundle:Registration r "
        ." Join r.meeting m"  
	      ." WHERE r.user = :idu"
        ." AND m.seminar = :ids"
        ." AND m.dateStart = :ds");
     $q->setParameter("ids", $seminar->getId());
     $q->setParameter("idu", $user['id']);
     $q->setParameter("ds", $meeting->getDateStart());
     return $q->getOneOrNullResult();
  }
  
}
