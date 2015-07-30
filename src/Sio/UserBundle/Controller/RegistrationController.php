<?php

namespace Sio\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;


/**
 * Redefine complete : RegistrationController (FOSUser)
 * 
 * @see http://symfony.com/fr/doc/current/cookbook/bundles/inheritance.html
 * 
 */
class RegistrationController extends BaseController {

  const ROUTE_LOGIN = 'fos_user_security_login';

  /* override */
  public function registerAction(Request $request) {
   
    $user = $this->getUser();
    if (!$user) :
      return $this->forward('SioUserBundle:User:register', array('request' => $request));
    else : 
      $this->get('session')->getFlashBag()->add('warning', '=> bad connect');   
      return $this->redirect($this->generateUrl(self::ROUTE_LOGIN));
    endif;
 
//    return parent::registerAction($request);
  }

}
