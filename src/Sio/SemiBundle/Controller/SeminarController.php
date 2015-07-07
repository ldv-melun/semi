<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sio\SemiBundle\Entity\Registration as Registration;
Use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/seminar")
 */
class SeminarController extends Controller {

    /**
     * @Route("/", name="_semi_seminar_index")
     * @Route("/{id}", name="_semi_seminar_index_seminar")
     * @Template()
     */
    public function indexAction($id = null) {
        $idSeminar = $id;
        $user = $this->getUser();
        $manager = $this->getDoctrine()->getManager();    
        $repoMeeting = $manager->getRepository('Sio\SemiBundle\Entity\Meeting');
        if ($idSeminar != NULL) {
            $seminar = $this->getDoctrine()->getRepository("SioSemiBundle:Seminar")->findOneBy(array("id" => $idSeminar));
            $meetings = $this->getDoctrine()->getRepository("SioSemiBundle:Meeting")->findBy(array("seminar" => $seminar), array('dateStart' => 'asc', 'relativeNumber' => 'asc'));
            $nbMeetingRegister = $repoMeeting->countNbMeetingRegister($user->getId(), $seminar->getId());
            $seminarState = $seminar->getState()->getName();
            $desSeances = array();
            $curJour = null;
            foreach ($meetings as $meeting) {
                $j = $this->jourFr(date("N", $meeting->getDateStart()->getTimestamp()));
                $day = $j . ' ' . date("d-m-Y", $meeting->getDateStart()->getTimestamp());
                $curJour = $day;
                $meeting->dispo = (int) ($meeting->getMaxSeats() - $repoMeeting->countSeatsTaken($meeting->getId()));
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
            return $this->render('SioSemiBundle:Seminar:meetings.html.twig', $toview);
        } else {
            $em = $this->getDoctrine()->getManager();

            // TODO à revoir cette section (recup directe des seminaires d'un user)

            $query = $em->createQuery(
                    'SELECT userseminar
                FROM SioSemiBundle:UserSeminar userseminar
                WHERE userseminar.user = ' . $user->getId() . '
                ORDER BY userseminar.id'
            );

            $userSeminar = $query->getResult();
            $repoSeminar = $this->getDoctrine()->getRepository("SioSemiBundle:Seminar");
            $seminars = array();
            foreach ($userSeminar as $seminarUser) {
                $seminars[] = $repoSeminar->findBy(array("id" => $seminarUser->getSeminar()->getId()));
            }

            // TODO Pourquoi remonter tous les meetings ici ?
            $meetings = array();
            
            // ne sachant pas j'isole le code douteux ci-dessous (kpu 06/7/2015)
            /*
            foreach ($seminars as $tab) {
                foreach ($tab as $seminar) {
                    // Une valeur $jour puis $DateDebut doit arriver entre []
                    $meetings[] = $repoMeeting->findBy(array("seminar" => $seminar->getId()));
                }
            }
            */
            return array("seminars" => $seminars, "meetings" => $meetings);
        }
    }

        
    /**
     * @return JSon ('statCurUser', 'statMeeting')
     * @Route("/ajax/register", name="_semi_user_ajax_registerOld")
     */
    public function ajaxChoiceRegistrationAction(Request $request) {
        $user = $this->getUser();
        
        $idSeance = $request->request->get('idSeance', null);
        $inscription = $request->request->get('inscrire', null);
        $dateHeureDebut = $request->request->get('dateHeureDebut', null);
        

        if ($idSeance == NULL || $inscription == NULL || $dateHeureDebut == NULL) {
            // This case can't normally happen.
            $response = new Response();
            $response->setStatusCode(500);
            $response->headers->set('Refresh', '0; url=' . $this->generateUrl('_semi_user_index'));
            $response->send();
            return;
        }

        $meeting = $this->getDoctrine()->getRepository("SioSemiBundle:Meeting")->findOneBy(array("id" => $idSeance));
        $seminar = $meeting->getSeminar();

        $manager = $this->getDoctrine()->getManager();
        $repoMeeting = $manager->getRepository('Sio\SemiBundle\Entity\Meeting');
        // Traitement.
        if ($inscription == 'true') {
            $repoMeeting->razInscriptionSeances($dateHeureDebut, $user, $seminar); 
            $newRegistration = new Registration();
            $newRegistration->setDateRegistration(new \DateTime('now'));
            $newRegistration->setUser($user);
            $newRegistration->setMeeting($meeting);
            $manager->persist($newRegistration);
            $manager->flush();
        } else {
            $repoMeeting->razInscriptionSeances($dateHeureDebut, $user, $seminar);
        }
        
        $statCurUser = $repoMeeting->countNbMeetingRegister($user, $seminar);
        $statMeeting = $repoMeeting->getStatInscriptionSeance($dateHeureDebut);
        
        return new JsonResponse(array('statCurUser'=>$statCurUser, 'statMeeting'=>$statMeeting));
    }

    static function jourFr($jour) {
        $jours = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
        return $jours[$jour - 1];
    }

}
