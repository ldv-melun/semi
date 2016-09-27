<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sio\SemiBundle\Util\Header as Header;
use Sio\SemiBundle\SioSemiConstants;

/**
 * @Route("/manager")
 */
class ManagerController extends Controller {

  /**
   * @Route("/", name="_semi_manager_index")
   * @Template()
   */
  public function indexAction(Request $request) {
    $idSeminar = $request->getSession()->get(SioSemiConstants::SEMINAR_ID);
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
    return $this->exportHTMLorCSV($idSeminar, false);
  }
  
  /**
   * @Route("/exportcsv", name="_semi_manager_export_csv")
   * @Template()
   */
  public function exportCSVAction(Request $request) {
    $idSeminar = $request->getSession()->get(SioSemiConstants::SEMINAR_ID);
    if ($idSeminar) {
       return $this->exportHTMLorCSV($idSeminar, true);
    } else {
      return $this->redirectToRoute('_semi_manager_index');
    }
  }
  
  public function exportHTMLorCSV($idSeminar, $isExportToCsv) {
    $repoSeminar =  $this->getDoctrine()
               ->getRepository('SioSemiBundle:Seminar');
  
    $repoMeeting =  $this->getDoctrine()
               ->getRepository('SioSemiBundle:Meeting');

    $repoReg =  $this->getDoctrine()
               ->getRepository('SioSemiBundle:Registration');

    $seminar =  $repoSeminar->find($idSeminar);
    
    $allUsersRegisted = $repoSeminar->getAllRegistrationsUserSeminar($seminar);
    $plagesHoraires   = $repoMeeting->getOnlyMeetingsWithDistinctsPlagesHoraires($seminar);
  
    $result = array();
    $header = array();
    foreach ($allUsersRegisted[0] as $head) :
      $header[]= $head;
    endforeach;
    // \setlocale(\LC_TIME, 'fr_FR.utf8','fra'); 
    foreach ($plagesHoraires as $meeting) :
      $type = $meeting->getType();
      //$data['date'] = \date("d-m-Y", $meeting->getDateStart()->getTimestamp());
      $hdate = \date("d-m-Y H:m", $meeting->getDateStart()->getTimestamp());       
      
      $header[] = new Header($hdate, $type);
    endforeach;
    $result[] = $header;
    $i = 0;

    // Oh ! out of memory whith profiler enable...
    // http://stackoverflow.com/questions/30229637/out-of-memory-error-in-symfony
    if ($this->container->has('profiler')) :
      $this->container->get('profiler')->disable();    
    endif;
    $firstLineHeader = true;
    foreach ($allUsersRegisted as $user) :
      if ($firstLineHeader) {
        $firstLineHeader = false;
        continue;
      }
      
      $row = array();
      foreach($user as $infoUser):
        $row[] = $infoUser;
      endforeach;        
      foreach ($plagesHoraires as $meeting) :        
         $registration = $repoMeeting->getMeetingUser($seminar, $user, $meeting);
         if ($registration) :
           $row[] = $registration->getMeeting()->getType() == 'atelier' 
             // ? $registration->getMeeting()->getRelativeNumber() 
             ? substr($registration->getMeeting()->getDescription(), 0, 10) 
             : 'X'; 
         else :
           $row[] = '-';
         endif;        
       endforeach;
      $result[] = $row;
    endforeach;

    // var_dump($result);
    
    
    if ($isExportToCsv) {
      $response = new Response();
      $response->headers->set('Content-Type', 'csv');
      $filename = "export_".date("Y_m_d_His").".csv";
      $response->headers->set('Content-Disposition', 'attachment; filename='.$filename); 
      return $this->render(
          'SioSemiBundle:Manager:export.csv.twig',
          array(
              'allRegistrations' => $result,
              'seminar' => $seminar,
          ),
          $response
      );
    } else {
      $toview = array(
        'seminar' => $seminar,
        'allRegistrations' => $result,
        'menuItemActive' => 'manager');
      return $toview;
    }
  }
  
}
