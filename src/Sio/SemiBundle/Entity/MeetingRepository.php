<?php

namespace Sio\SemiBundle\Entity;

use Doctrine\ORM\EntityRepository;

class MeetingRepository extends EntityRepository {

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

}
