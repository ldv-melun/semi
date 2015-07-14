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
    $seminar =  $this->getDoctrine()
               ->getRepository('SioSemiBundle:Seminar')
               ->find($idSeminar);

    $toview = array(
        'seminar' => $seminar,
        'menuItemActive' => 'manager');
    return $toview;
  }

}
