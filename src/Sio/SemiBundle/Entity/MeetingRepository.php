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
   * @param date_sql $dateStart 
   */
  public function getStatInscriptionSeance($dateStart) {
    $rsm = new ResultSetMapping;
    $rsm->addScalarResult('id', 'id');
    $rsm->addScalarResult('free', 'free');
    $rsm->addScalarResult('maxSeats', 'maxSeats');

    $sql = 'SELECT semi_meeting.id, maxSeats-count(idMeeting) AS free, maxSeats FROM semi_meeting LEFT JOIN semi_registration ON semi_meeting.id=idMeeting WHERE dateStart = ? GROUP BY semi_meeting.id';

    $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
    $query->setParameter(1, $dateStart);

    return $query->getResult();
  }

}
