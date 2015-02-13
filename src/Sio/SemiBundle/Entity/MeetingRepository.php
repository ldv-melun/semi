<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\EntityRepository;

class MeetingRepository extends EntityRepository {
    /* GLOBAL NOTES :
     * Seminar isn't needed in all of the functions.
     * idMeeting is linked to a Seminar.
     */

    /**
     * Used to count the number of seats taken.
     * @param \Meeting $meeting
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
     * @param \User $user
     * @param \Seminar $seminar
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
        return $dateHeureDebut;
    }

}
