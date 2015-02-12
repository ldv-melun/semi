<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sio\SemiBundle\Entity\Seminar as Seminar;

/**
 * @Route("/user")
 */
class UserController extends Controller {

    /**
     * @Route("/", name="_semi_user_index")
     * @Route("/{idSeminar}", name="_semi_user_index_seminar")
     * @Template()
     */
    public function indexAction($idSeminar = null) {
        $user = $this->getUser();

        if ($idSeminar != NULL) {
            $seminar = $this->getDoctrine()->getRepository("SioSemiBundle:Seminar")->findOneBy(array("id" => $idSeminar));
            $meetings = $this->getDoctrine()->getRepository("SioSemiBundle:Meeting")->findBy(array("seminar" => $seminar), array('dateStart' => 'asc', 'relativeNumber' => 'asc'));
            $manager = $this->getDoctrine()->getManager();
            $nbMeetingRegister = $manager->getRepository('Sio\SemiBundle\Entity\Meeting')->countNbMeetingRegister($user->getId(), $seminar->getId());

            $curJour = null;
            foreach ($meetings as $meeting) {
                $j = $this->jourFr(date("N", $meeting->getDateStart()->getTimestamp()));
                $day = $j . ' ' . date("d-m-Y", $meeting->getDateStart()->getTimestamp());
                $curJour = $day;
                $meeting->dispo = (int) ($meeting->getMaxSeats() - $manager->getRepository('Sio\SemiBundle\Entity\Meeting')->countSeatsTaken($meeting->getId()));
                $meeting->realDateHeureDebut = $meeting->getDateStart();
                $meeting->dateHeureDebut = date("H:i", $meeting->getDateStart()->getTimestamp());
                $meeting->dateHeureFin = date("H:i", $meeting->getDateEnd()->getTimestamp());
                $heureDeb = $meeting->dateHeureDebut;
                // les seances sont stockees par jour et heureDeb
                $desSeances[$curJour][$heureDeb][] = $meeting;
            }
            
            $toview = array("title" => $seminar->getName(), "description" => $seminar->getDescription(), "meetings" => $desSeances, "nbMeetingRegister" => $nbMeetingRegister);
            return $this->render('SioSemiBundle:User:meetings.html.twig', $toview);
        } else {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                    'SELECT userseminar
                FROM SioSemiBundle:UserSeminar userseminar
                WHERE userseminar.user = ' . $user->getId() . '
                ORDER BY userseminar.id'
            );

            // TODO à revoir (recup directe des seminaires d'un user)

            $userSeminar = $query->getResult();
            $seminars = array();
            foreach ($userSeminar as $seminar) {
                $seminars[] = $this->getDoctrine()->getRepository("SioSemiBundle:Seminar")->findBy(array("id" => $seminar->getSeminar()->getId()));
            }

            foreach ($seminars as $tab) {
                foreach ($tab as $seminar) {
                    // Une valeur $jour puis $DateDebut doit arriver entre []
                    $meetings[] = $this->getDoctrine()->getRepository("SioSemiBundle:Meeting")->findBy(array("seminar" => $seminar->getId()));
                }
            }

            return array("seminars" => $seminars, "meetings" => $meetings);
        }
    }

    /**
     * @Route("/", name="_semi_user_register")
     * @Template()
     */
    public function registerAction() {
        
    }

    /**
     * @Route("/", name="_semi_user_profil")
     * @Template()
     */
    public function profilAction() {
        
    }

    static function jourFr($jour) {
        $jours = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
        return $jours[$jour - 1];
    }

}
