<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/manager")
 */
class ManagerController extends Controller {

  /**
   * @Route("/", name="_semi_manager_index")
   * @Template()
   */
  public function indexAction(Request $request) {
    $idSeminar = $request->getSession()->get(DefaultController::SEMINAR_ID);
    if ($idSeminar) {
      $return = $this->forward('SioSemiBundle:Manager:export', array('idSeminar'=>$idSeminar));
    } else {
      $return  = array(
          'menuItemActive' => 'manager');
    }
    return $return;
  }

  /**
   * @Route("/export/{idSeminar}", name="_semi_manager_export")
   * @Template()
   */
  public function exportAction($idSeminar) {
    $repoSeminar =  $this->getDoctrine()
               ->getRepository('SioSemiBundle:Seminar');
  
    $seminar =  $repoSeminar->find($idSeminar);

    
    $allRegistrations = $repoSeminar->getAllRegistrationsUserSeminar($seminar);
    
    $toview = array(
        'seminar' => $seminar,
        'allRegistrations' => $allRegistrations,
        'menuItemActive' => 'manager');
    return $toview;
  }
  
  /*
   * 
  private function getAllRegistrationsBySeminar($seminar) {
    $manager = $this->getDoctrine()->getManager();  
     // etape 0 : récupérer les plages d'atelier : tabAtelier
    $lesPlagesAtelier =  $pdoSemi->getLesPlagesAtelier($idSem);
    // etape 1 : creation de la première ligne
    $liste = "ACADEMIE;NOM;PRENOM";
    $i = 1;
    foreach ($lesPlagesAtelier as $plage) {
      $j = PdoSeminaire::jourFr(date("N", strtotime($plage['dateHeureDebut'])));
      $day = $j . ' ' . date("d-m-Y::H:i", strtotime($plage['dateHeureDebut']));
      $liste .= '; ('. $i++ .') ' . $day;
    }
    // etape 2 : obtenir les participants           
    $lesParticipants = $pdoSemi->getLesInscritsSeminaire($idSem);
    //etape 3 : rechercher les libellés ateliers inscrits 
    foreach ($lesParticipants as $participant) :
        $libAteliers = array();
        foreach ($lesPlagesAtelier as $plage) :
          $res = $pdoSemi->getLibAtelier($plage['dateHeureDebut'], $participant['id'], $idSem);
          $libAteliers[$plage['dateHeureDebut']] = ($res) ? $res->libelle : '';                              
        endforeach;
        //etape 4 : construction de la ligne
        $liste .= "\n" . $participant['acad'] . ';' . $participant['nom'] .';' .$participant['prenom'];
        foreach ($libAteliers as $key=>$libelle) :
             $liste .= ';' . $libelle;                   
        endforeach;
    endforeach; 
    return $liste;   
   */
  
  /**
   * Get meetings with distinct dateStart (one representant by atelier) 
   * @param Seminar $seminar
   * @return Array of Meeting
   */
  private function getMeetingsDistinctDateStart($seminar){
    $em = $this->getDoctrine()->getManager();  
    $query = $em->createQuery('SELECT m FROM SioSemiBundle:Meeting m '
          .'WHERE m.seminar = :sem AND m.relativeNumber = 1 ORDER BY m.dateStart ASC');
    $query->setParameter("sem", $seminar);  
    return $query->getResult();
  }
  /*
  private function getAllRegistrationsBySeminarOld($seminar) {
    $manager = $this->getDoctrine()->getManager();  
     // etape 0 : récupérer les plages horaires 
    $meetingsDistinctDateStart =  $this->getMeetingsDistinctDateStart($seminar);
    // etape 1 : creation de la première ligne
    $liste = array("ACADEMIE","NOM","PRENOM");
    $i = 1;
    foreach ($meetingsDistinctDateStart as $meeting) {
      $j = SeminarController::jourFr(date("N", $meeting->getDateStart()->getTimestamp()));
      $day = $j . ' ' . date("d-m-Y", $meeting->getDateStart()->getTimestamp());
      $liste[] = $day;
    }

    $repoSeminar = $this->getDoctrine()
              ->getRepository('SioSemiBundle:Seminar');
    // etape 2 : obtenir les participants           
    $lesParticipants = $repoSeminar->getAllRegistrationsUserSeminar($seminar);

    $result = array();
    $result[]=$liste;
    foreach ($lesParticipants as $r) :
      $row = array();
      $row[]= $r->getUser()->getOrganisation()->getName();
      $row[]= $r->getUser()->getLastName();
      $row[]= $r->getUser()->getFirstName();
      $result[]=$row;
    endforeach;
    return $result;
    /*
    //etape 3 : rechercher les libellés ateliers inscrits 
    foreach ($lesParticipants as $registration) :
        $libAteliers = array();
        foreach ($lesPlagesAtelier as $plage) :
          $res = $pdoSemi->getLibAtelier($plage['dateHeureDebut'], $participant['id'], $idSem);
          $libAteliers[$plage['dateHeureDebut']] = ($res) ? $res->libelle : '';                              
        endforeach;
        //etape 4 : construction de la ligne
        $liste .= "\n" . $registration->user->organisation etc. . ';' . $participant['nom'] .';' .$participant['prenom'];
        foreach ($libAteliers as $key=>$libelle) :
             $liste .= ';' . $libelle;                   
        endforeach;
    endforeach; 
    return $liste;       
    
    
    $em = $this->getDoctrine()->getManager();
    $query = $em->createQuery('SELECT m, r FROM SioSemiBundle:Meeting m '
          .'LEFTJOIN r.meeting meeting '
          .'JOIN meeting.seminar sem '
          .'WHERE r.user = :user AND sem = :sem');
      $query->setParameter("user", $user);  
    
    
    
    
    
    $em = $this->getDoctrine()->getManager();
    $query = $em->createQuery('SELECT m, r FROM SioSemiBundle:Meeting m '
          .'LEFTJOIN r.meeting meeting '
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
   }
      */
  
}
