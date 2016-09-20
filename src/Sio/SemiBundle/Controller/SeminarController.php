<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sio\SemiBundle\Entity\Registration as Registration;
Use Symfony\Component\HttpFoundation\JsonResponse;
Use Sio\SemiBundle\SioSemiConstants;
/**
 * @Route("/seminar")
 */
class SeminarController extends Controller {

  
  /**
   * Show recorded meetings for a seminar and user given
   * 
   * @Route("/mymeetings/{id}", name="_semi_seminar_my_meetings")
   */
  public function myMeetingsAction(Request $request, $id) {
    if ($id) { 
      return $this->getMeetings($request, $id, true);
    } else {
      // TODO throw new ;
      return $this->redirect($this->generateUrl('_semi_default_index'));
    }
  }
  
  /**
   * Show seminars list or direct meetings for a seminar given
   * 
   * @Route("/", name="_semi_seminar_index")
   * @Route("/{id}", name="_semi_seminar_index_seminar")
   * @Template()
   */
  public function indexAction(Request $request, $id = null) {
    if ($id) {            
      return $this->getMeetings($request, $id, false);      
    } else {
      $user = $this->getUser();
      $em = $this->getDoctrine()->getManager();
      // TODO à revoir cette section (recup directe des seminaires d'un user)
      $query = $em->createQuery(
          'SELECT userseminar
                FROM SioSemiBundle:StatusUserSeminar userseminar
                WHERE userseminar.user = ' . $user->getId() . '
                ORDER BY userseminar.id'
      );

      $userSeminar = $query->getResult();
      $repoSeminar = $this->getDoctrine()->getRepository("SioSemiBundle:Seminar");
      $seminars = array();
      foreach ($userSeminar as $seminarUser) {
        $seminars[] = $repoSeminar->findBy(array("id" => $seminarUser->getSeminar()->getId()));
      }
      return array('seminars' => $seminars);  
    }
  }

  private function getMeetings($request, $idSeminar, $isOnlyMyRegistrations) {
    $user = $this->getUser();
    $manager = $this->getDoctrine()->getManager();
    $repoMeeting = $manager->getRepository('Sio\SemiBundle\Entity\Meeting');

    $seminar = $this->getDoctrine()->getRepository("SioSemiBundle:Seminar")->findOneBy(array("id" => $idSeminar));
    if (!$seminar) {
      // TODO throw exception ? 
      return $this->redirect($this->generateUrl('_semi_default_index'));
    }
    
    // user can go on this seminar ? (if hack url :)
    $seminarStatus = $this->getDoctrine()
        ->getRepository('SioSemiBundle:StatusUserSeminar')
        ->findOneBy(array('user' => $user,
                          'seminar' => $seminar));
    if (!$seminarStatus) {
      // TODO throw exception ? 
      return $this->redirect($this->generateUrl('_semi_default_index'));
    }
    
    // set idSeminar in session
    $request->getSession()->set(SioSemiConstants::SEMINAR_ID, $idSeminar);
     
    if ($isOnlyMyRegistrations) {
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery('SELECT r, meeting, sem FROM SioSemiBundle:Registration r '
          .'JOIN r.meeting meeting '
          .'JOIN meeting.seminar sem '
          .'WHERE r.user = :user AND sem = :sem');
      $query->setParameter("user", $user);  
      $query->setParameter("sem", $seminar);  
      $result = $query->getResult();
      $meetings = array();
      // get meetings only (TODO avoid this ?)
      foreach ($result as $r) {
        $meetings[] = $r->getMeeting();
      }
       
      // $this->get('session')->getFlashBag()->add('INFO', 'nb de lignes ('.$idSeminar.') =' . count($meetings) );;
        
    } else {
      $meetings = $this->getDoctrine()->getRepository("SioSemiBundle:Meeting")
          ->findBy(array('seminar' => $seminar), 
                   array('dateStart' => 'asc', 'relativeNumber' => 'asc'));
    }
    
    $nbMeetingRegister = $repoMeeting->countNbMeetingRegister($user->getId(), $seminar->getId());
    $seminarState = $seminar->getState()->getName();
    $meetingsForView = array();
    $curDay = null;
    foreach ($meetings as $meeting) {
      $j = self::jourFr(date("N", $meeting->getDateStart()->getTimestamp()));
      $day = $j . ' ' . date("d-m-Y", $meeting->getDateStart()->getTimestamp());
      $curDay = $day;
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
      $dateTimeStart = $meeting->dateHeureDebut;
      // meetings recorded by day and dateTimeStart               
      $meetingsForView[$curDay][$dateTimeStart][] = $meeting;
    }
    $toview = array('title' => $seminar->getName(),
        'description' => $seminar->getDescription(),
        'meetings' => $meetingsForView,
        'nbMeetingRegister' => $nbMeetingRegister,
        'readOnly' => $isOnlyMyRegistrations || $seminarState != 'Open',
        'seminar' => $seminar ); // TODO del attribut because accessible by seminar
    return $this->render('SioSemiBundle:Seminar:meetings.html.twig', $toview);
  }

  /**
   * @return JSon ('statCurUser', 'statMeeting')
   * @Route("/ajax/register", name="_semi_seminar_ajax_register")
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
      $response->headers->set('Refresh', '0; url=' . $this->generateUrl('_semi_default_index'));
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
    
    // less data send...
    //$statMeeting = $repoMeeting->getStatInscriptionSeance($seminar, $dateHeureDebut);
    
    $statMeeting = $repoMeeting->getStatInscriptionsSeancesBySeminar($seminar);

    return new JsonResponse(array('statCurUser' => $statCurUser, 'statMeeting' => $statMeeting));
  }

    
  /**
   * @return JSon ('statMeeting')
   * @Route("/ajax/stateregistration", name="_semi_seminar_ajax_stateregistration")
   */
  public function ajaxStateRegistrationAction(Request $request) {
    $idSeminar = $request->getSession()->get(SioSemiConstants::SEMINAR_ID);
    $seminar = $this->getDoctrine()->getRepository("SioSemiBundle:Seminar")->findOneBy(array("id" => $idSeminar));
    if (!$seminar) {
    // This case can't normally happen.
      $response = new Response();
      $response->setStatusCode(500);
      $response->headers->set('Refresh', '0; url=' . $this->generateUrl('_semi_default_index'));
      $response->send();
      return;
    }
    $manager = $this->getDoctrine()->getManager();
    $repoMeeting = $manager->getRepository('Sio\SemiBundle\Entity\Meeting');
    $statMeeting = $repoMeeting->getStatInscriptionsSeancesBySeminar($seminar); 
    
    return new JsonResponse(array('statMeeting' => $statMeeting));
  }
  
  
  static function jourFr($jour) {
    $jours = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
    return $jours[$jour - 1];
  }

}
