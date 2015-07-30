<?php

namespace Sio\UserBundle\Service;

use Sio\SemiBundle\Controller\DefaultController as SemiDefaultController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;

/**
 * Description of HelperRegistration
 * création d'un service dans le but d'être utilisé dans un from type 
 * créé pour être utilisé par FUB
 * 
 *   http://stackoverflow.com/questions/18223988/symfony2-access-user-and-doctrine-in-a-service
 * 
 * @author kpu
 */

class HelperRegistration {
  
  protected $requestStack;
  protected $em;
  protected $securityContext;
  
  public function __construct(
       EntityManager $em, 
       SecurityContext $securityContext, 
       RequestStack $requestStack)
  {
    $this->requestStack = $requestStack;
    $this->em = $em;
    $this->securityContext = $securityContext;
  }
    
  /**
   * 
   */
  public function getStatusUserSeminar()
  {
    $session = $this->requestStack->getCurrentRequest()->getSession();
    $user = $this->securityContext->getToken()->getUser();
      
    $res = array();
    $res['allStatusUserSeminar'] = NULL;
    $res['idStatus'] = NULL;
    
    $seminarId = $session->get(SemiDefaultController::SEMINAR_ID);
    if ($seminarId) :      
      $repoSeminar = $this->em->getRepository('SioSemiBundle:Seminar');
      $seminar = $repoSeminar->find($seminarId);    
      if ($seminar) :
        $res['allStatusUserSeminar'] = $repoSeminar->getAllUserStatusBySeminar($seminar);
        $res['idStatus'] = $this->getIdStatusByUser($user, $seminar);
      endif;
    endif;
    
    return $res;
  }
    
  /**
   * 
   * @param User $user
   * @param Seminar $seminar
   * @return id of user status for this seminar or null
   */
  function getIdStatusByUser($user, $seminar) {
    $status = $this->em->getRepository('SioSemiBundle:UserSeminar')
            ->findOneBy(array('seminar' => $seminar, 'user'=> $user));
    if ($status) :
      $idStatus = $status->getStatus()->getId();
    else :
      $idStatus = null; 
    endif;
   
    return $idStatus;
  }
    
}
