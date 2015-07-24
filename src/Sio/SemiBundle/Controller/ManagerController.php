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
  
    $repoMeeting =  $this->getDoctrine()
               ->getRepository('SioSemiBundle:Meeting');

    $repoReg =  $this->getDoctrine()
               ->getRepository('SioSemiBundle:Registration');

    
    $seminar =  $repoSeminar->find($idSeminar);
    
    $allUsersRegisted = $repoSeminar->getAllRegistrationsUserSeminar($seminar);
    $plagesHoraires   = $repoMeeting->getAllDistinctsPlagesHoraires($seminar);
    
    $result = array();
    
    foreach ($allUsersRegisted[0] as $header) :
      $result[]= $header;
    endforeach;
      
    foreach ($plagesHoraires as $meeting) :
      $result[] = $meeting->getDateStart();
    endforeach;
    $i = 0;

    // Oh ! out of memory whith profiler enable...
    // http://stackoverflow.com/questions/30229637/out-of-memory-error-in-symfony
    if ($this->container->has('profiler')) :
      $this->container->get('profiler')->disable();    
    endif;

    foreach ($allUsersRegisted as $user) :
      $row = array();
      if ($i++ == 0) : 
        foreach($user as $header):
          $row[] = $header;
        endforeach;
        foreach ($plagesHoraires as $meeting) :
          $data = array();
          $data['type'] = $meeting->getType();
          //$data['date'] = \date("d-m-Y", $meeting->getDateStart()->getTimestamp());
          $data['date'] = \date("F j, Y, H:m", $meeting->getDateStart()->getTimestamp());
          
          $row[] = $data['date'];
        endforeach;
      else :
        foreach($user as $infoUser):
          $row[] = $infoUser;
        endforeach;        
       foreach ($plagesHoraires as $meeting) :
         $registration = $repoMeeting->getMeetingUser($seminar, $user, $meeting);
         if ($registration) :
           $row[] = $registration->getMeeting()->getType() == 'atelier' 
             ? $registration->getMeeting()->getRelativeNumber() 
             : 'X'; 
         else :
           $row[] = '-';
         endif;  
         
       endforeach;
      endif;
      $result[] = $row;
    endforeach;
      
    $toview = array(
        'seminar' => $seminar,
        'allRegistrations' => $result,
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
