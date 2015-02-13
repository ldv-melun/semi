<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sio\SemiBundle\Entity\Registration as Registration;
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
            $seminarState = $seminar->getState()->getName();

            $curJour = null;
            foreach ($meetings as $meeting) {
                $j = $this->jourFr(date("N", $meeting->getDateStart()->getTimestamp()));
                $day = $j . ' ' . date("d-m-Y", $meeting->getDateStart()->getTimestamp());
                $curJour = $day;
                $meeting->dispo = (int) ($meeting->getMaxSeats() - $manager->getRepository('Sio\SemiBundle\Entity\Meeting')->countSeatsTaken($meeting->getId()));
                $meeting->realDateHeureDebut = $meeting->getDateStart();
                $meeting->dateHeureDebut = date("H:i", $meeting->getDateStart()->getTimestamp());
                $meeting->dateHeureFin = date("H:i", $meeting->getDateEnd()->getTimestamp());
                $registering = $this->getDoctrine()->getRepository("SioSemiBundle:Registration")->findOneBy(array("meeting" => $meeting->getId(), "user" => $user->getId()));
                if ($registering) {
                    $meeting->register = 1;
                } else {
                    $meeting->register = 0;
                }
                $heureDeb = $meeting->dateHeureDebut;
                // les seances sont stockees par jour et heureDeb
                $desSeances[$curJour][$heureDeb][] = $meeting;
            }

            $toview = array("title" => $seminar->getName(), "description" => $seminar->getDescription(), "meetings" => $desSeances, "nbMeetingRegister" => $nbMeetingRegister, "stateSeminar" => $seminarState);
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

    /**
     * @Route("/ajax/register", name="_semi_user_ajax_register")
     * @Template()
     */
    public function ajaxAction(Request $request) {
        $user = $this->getUser();
        $return = NULL;

        $idSeance = $request->request->get('idSeance', null);
        $inscription = $request->request->get('inscrire', null);
        $dateHeureDebut = $request->request->get('dateHeureDebut', null);
        $raz = $request->request->get('raz', null); // OPT

        if ($idSeance == NULL || $inscription == NULL || $dateHeureDebut == NULL) {
            // This case can't normally happen.
            $response = new Response();
            $response->setStatusCode(500);
            $response->headers->set('Refresh', '0; url=' . $this->generateUrl('_semi_user_index'));
            $response->send();
        }

        $meeting = $this->getDoctrine()->getRepository("SioSemiBundle:Meeting")->findOneBy(array("id" => $idSeance));
        $seminar = $this->getDoctrine()->getRepository("SioSemiBundle:Seminar")->findOneBy(array("id" => $meeting->getSeminar()));

        $manager = $this->getDoctrine()->getManager();

        // Traitement.
        if ($raz != NULL) {
            $return = $manager->getRepository('Sio\SemiBundle\Entity\Meeting')->razInscriptionSeances($dateHeureDebut, $user, $seminar); // 1 | 0
        } elseif ($inscription == 'true') {
            $return = $manager->getRepository('Sio\SemiBundle\Entity\Meeting')->razInscriptionSeances($dateHeureDebut, $user, $seminar); // 1 | 0
            // TODO : Refactor.
            $newRegistration = new Registration();
            $newRegistration->setDateRegistration(new \DateTime('now'));
            $newRegistration->setUser($user);
            $newRegistration->setMeeting($meeting);

            $em = $this->getDoctrine()->getManager();
            $em->persist($newRegistration);
            $em->flush();
            $return = 1;
        } else {
            $return = $manager->getRepository('Sio\SemiBundle\Entity\Meeting')->razInscriptionSeances($dateHeureDebut, $user, $seminar); // 1 | 0
        }

        return array('return' => $return);
    }

    static function jourFr($jour) {
        $jours = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
        return $jours[$jour - 1];
    }

}
