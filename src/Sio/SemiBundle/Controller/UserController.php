<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * @Route("/user")
 */
class UserController extends Controller {

  /**
   * prepare for view of register, so some duplicate instructions 
   * from registerUser method of RegistrationController... TODO no duplicate 
   *  
   * @Route("/user/profil", name="_semi_user_profil")
   * @Template()
   */
  public function updateProfilAction(Request $request) {
    $user = $this->getUser();
    $seminarKey = null;
    $session = $request->getSession();

    if ($session->has('seminarClef')) {
      $seminarKey = $session->get('seminarClef');
    }

    $manager = $this->getDoctrine()->getManager();
    $repoSeminar = $manager->getRepository('SioSemiBundle:Seminar');
    $seminar = $repoSeminar->findOneByClef($seminarKey);
    
    $userStatus = '';
    $allStatus = array();
    
    if ($seminar) {
      // define status for this seminar
      // (a user can direct connect without seminar key)
      $allStatus = $repoSeminar->getAllStatusBySeminar($seminar);
      $userStatus = $this->getDoctrine()
          ->getRepository('SioSemiBundle:UserSeminar')
          ->findOneBy(array('user' => $user, 'seminar' => $seminar))
          ->getStatus()->getStatus();
    }
      
    $organisations = $this->getDoctrine()
          ->getRepository('SioSemiBundle:Organisation')
          ->findAll();
      
    $typeOrganisation = $this->getDoctrine()
          ->getRepository('SioSemiBundle:Parameter')
          ->findOneBy(array('clef' => 'organisation'));
      
    $toview = array(
        'user' => $user,
        'clef' => $seminarKey,
        'organisations' => $organisations,
        'paramOrganisation' => $typeOrganisation,
        'allStatus' => $allStatus,
        'userStatus' => $userStatus
        );
    return $this
            ->render('SioUserBundle:Registration:register.html.twig', $toview);
  }

}
