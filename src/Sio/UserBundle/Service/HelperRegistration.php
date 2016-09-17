<?php

namespace Sio\UserBundle\Service;

use Sio\SemiBundle\SioSemiConstants;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;
use Sio\SemiBundle\Entity\UserSeminar;

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
   EntityManager $em, SecurityContext $securityContext, RequestStack $requestStack) {
    $this->requestStack = $requestStack;
    $this->em = $em;
    $this->securityContext = $securityContext;
  }

  /**
   * 
   */
  public function getStatusUserSeminar() {
    $session = $this->requestStack->getCurrentRequest()->getSession();
    $user = $this->securityContext->getToken()->getUser();

    $res = array();
    $res['allStatusUserSeminar'] = NULL;
    $res['idStatus'] = NULL;

    $seminarId = $session->get(SioSemiConstants::SEMINAR_ID);
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
    $status = $this->em->getRepository('SioSemiBundle:StatusUserSeminar')
        ->findOneBy(array('seminar' => $seminar, 'user' => $user));
    if ($status) :
      $idStatus = $status->getStatus()->getId();
    else :
      $idStatus = null;
    endif;

    return $idStatus;
  }

  /**
   * Update or create a UserSeminar Status
   * @param String $idStatus, if null get value from request form
   */
  public function registerUserSeminar($idStatus=null) {
    if (!$idStatus): // search into form   
      $request = $this->requestStack->getCurrentRequest()->request;
      $defaultValue = '1';
      $idStatus = $request->getAlnum('fos_user_registration_form[status]'
                                     , $defaultValue, true);      
    endif;
    
    $session = $this->requestStack->getCurrentRequest()->getSession();
    $user = $this->securityContext->getToken()->getUser();
    $seminarId = $session->get(SioSemiConstants::SEMINAR_ID);
    if ($seminarId) :
      $repoSeminar = $this->em->getRepository('SioSemiBundle:Seminar');
      $seminar = $repoSeminar->find($seminarId);
      if ($seminar) :
        $userStatus = //$this->getDoctrine()
            $this->em->getRepository('SioSemiBundle:Status')
            ->find($idStatus);

        $statusUserSeminar = //$this->getDoctrine()
            $this->em->getRepository('SioSemiBundle:StatusUserSeminar')
            ->findOneBy(array('seminar' => $seminar
            , 'user' => $user));

        if ($statusUserSeminar) :
          // update
          $statusUserSeminar->setStatus($userStatus);
          $this->em->persist($statusUserSeminar);
          $this->em->flush();
          // $session->getFlashBag()->add('success', 'Satus Update ' . $statusUserSeminar);
        else :
          // create
          $newUserSeminar = new UserSeminar();
          $newUserSeminar->setSeminar($seminar);
          $newUserSeminar->setStatus($userStatus);
          $newUserSeminar->setUser($user);
          $this->em->persist($newUserSeminar);
          $this->em->flush();
          // $session->getFlashBag()->add('success', 'Satus Create');
        endif;
      endif;
    endif;
  }

}
