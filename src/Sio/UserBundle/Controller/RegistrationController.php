<?php

namespace Sio\UserBundle\Controller;

Use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Sio\UserBundle\Entity\User as User;
use Sio\SemiBundle\Entity\UserSeminar as UserSeminar;

/**
 * Redefine complete : RegistrationController (FOSUser)
 * 
 * @see http://symfony.com/fr/doc/current/cookbook/bundles/inheritance.html
 * 
 */
class RegistrationController extends BaseController {

  const ROUTE_LOGIN = 'fos_user_security_login';

  public function registerAction(Request $request) {
    // $response = parent::registerAction();
    // do custom stuff
    return $this->registerUser($request);
  }

  private function registerUser(Request $request) {

    $session = $request->getSession();

    if (!$session->has('seminarClef') || !$session->has('emailUser')) {
      return $this->redirect($this->generateUrl(self::ROUTE_LOGIN));
    }

    $postRequest = $request->request;
    
    $userStatus = '';
      
    $user = $this->getUser();
    
    $mail = $session->get('emailUser');
    $clef = $session->get('seminarClef');

    // We will verify the clef and the seminar linked to it now and for all cases.
    $seminar = $this->getDoctrine()
        ->getRepository('SioSemiBundle:Seminar')
        ->findOneBy(array('clef' => $clef));

    if (!($this->checkIfSeminarExist($seminar))) {
      $this->get('session')
          ->getFlashBag()
          ->add('danger', 'La clé d\'inscription est invalide !');
      return $this->redirect($this->generateUrl(self::ROUTE_LOGIN));
    }

    // We wil get the seminar and all status from that Seminar for all cases.
    $allStatus = $this->getAllStatusBySeminar($seminar);

    if ($request->isMethod('POST')) {
      $this->doRegisterOrUpdateUserIfOk($postRequest, $seminar);
      return $this->redirect($this->generateUrl('_semi_user_index'));
    } else {
      // TODO refactor this bad method prepareRegister !!!
      $this->prepareRegister($seminar, $mail, $clef, $allStatus);
      
      $organisations = $this->getDoctrine()
          ->getRepository('SioSemiBundle:Organisation')
          ->findAll();
      
      //TODO renommer organisation par typeOrganisation dans parameter
      $typeOrganisation = $this->getDoctrine()
          ->getRepository('SioSemiBundle:Parameter')
          ->findOneBy(array('clef' => 'organisation'));
      
      
      if ($seminar && $user) {
        // find status for this user in this seminar
        $userStatus = $this->getDoctrine()
          ->getRepository('SioSemiBundle:UserSeminar')
          ->findOneBy(array('user' => $user, 'seminar' => $seminar))
          ->getStatus()->getStatus();
      }
      
      $toview = array(
          'mail' => $mail, 
          'clef' => $clef,
          'organisations' => $organisations,
          'paramOrganisation' => $typeOrganisation,
          'user' => new User(),
          'allStatus' => $allStatus,
          'userStatus' => $userStatus);
      
      return $this
              ->render('SioUserBundle:Registration:register.html.twig', $toview);
    }
  }

  /**
   * Determine if date now inner [$dateStart..$dateEnd]
   * @param \DateTime $dateStart
   * @param \DateTime $dateEnd
   * @return boolean
   */
  function allowRegistration($dateStart, $dateEnd) {
    $now = new \DateTime();
    return $dateStart <= $now && $now <= $dateEnd;
  }

  /**
   * Compare 2 values and return a boolean.
   * @param String $value1
   * @param String value2
   * @return boolean
   */
  function checkIfEquals($value1, $value2) {
    if ($value1 == $value2) {
      return true;
    }
    return false;
  }

  /**
   * Persist a user in the database and return that user
   * @param String $mail
   * @param String $lastName
   * @param String $firstName
   * @param String $jobCity
   * @param String $homeCity
   * @param String $pass
   * @param String $organisation
   * @return User
   */
  function doRegisterUser($mail, $lastName, $firstName, $jobCity, $homeCity, $pass, $organisation) {
    $user = $this->getUser();
    if (!$user) {
      // new user
      $newUser = new User();
      $newUser->setUsername($mail); // FOSUser say not null (and unique ? so we put mail)
      $newUser->setLastName($lastName);
      $newUser->setFirstName($firstName);
      $newUser->setJobCity($jobCity);
      $newUser->setHomeCity($homeCity);
      $newUser->setEmail($mail);
      $factory = $this->get('security.encoder_factory');
      $encoder = $factory->getEncoder($newUser);

      $password = $encoder->encodePassword($pass, null);
      // whith bcrypt salt will be generate and integrate into password
      // http://stackoverflow.com/questions/25760520/does-symfony-derive-the-salt-from-the-hash-or-isnt-the-hash-salted-at-all
      $newUser->setPassword($password);
      $newUser->setRoles(array("ROLE_USER"));
      $newUser->setEnabled(true);

      // Get the Organisation.
      $organisationQuery = $this->getDoctrine()
          ->getRepository('SioSemiBundle:Organisation')
          ->findOneBy(array('id' => $organisation));
      $newUser->setOrganisation($organisationQuery);

      $this->managerPersist($newUser);
      return $newUser;
    } else {
      // update : no change email, it is id
      if (!empty($lastName)) {
        $user->setLastName($lastName);
      }
      if (!empty($firstName)) {
        $user->setFirstName($firstName);
      }
      if (!empty($jobCity)) {
        $user->setJobCity($jobCity);
      }
      if (!empty($homeCity)) {
        $user->setHomeCity($homeCity);
      }
      
      // Get the Organisation.
      $organisationObject = $this->getDoctrine()
          ->getRepository('SioSemiBundle:Organisation')
          ->findOneBy(array('id' => $organisation));
      $user->setOrganisation($organisationObject);

      if (!empty($pass) && strlen($pass) >= 4) { // TODO more constraint ?...
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword($pass, null);
        $user->setPassword($password);
      } elseif (!empty($pass)) {
        $this->get('session')->getFlashBag()->add('warning', '(Password unchanged - too short (min 4 car))');
      }
      
      $this->managerPersist($user);
      return $user;
    }
  }

  /**
   * Persist a new UserSeminar Status
   * @param Seminar $seminar
   * @param User $user
   * @param String $status
   */
  function registerUserSeminar($seminar, $user, $status ) {
    $userStatus = $this->getDoctrine()
        ->getRepository('SioSemiBundle:Status')
        ->findOneBy(array("status" => $status));
 
    $statusUserSeminar = $this->getDoctrine()
        ->getRepository('SioSemiBundle:UserSeminar')
        ->findOneBy(array('seminar' => $seminar
        , 'user'=> $user));
    
    if ($statusUserSeminar) {
      $statusUserSeminar->setStatus($userStatus);
      $this->managerPersist($statusUserSeminar);
      $this->get('session')->getFlashBag()->add('success', 'Satus Update ' . $statusUserSeminar);
    } else {
      $newUserSeminar = new UserSeminar();
      $newUserSeminar->setSeminar($seminar);
      $newUserSeminar->setStatus($userStatus);
      $newUserSeminar->setUser($user);
      $this->managerPersist($newUserSeminar);
      $this->get('session')->getFlashBag()->add('success', 'Satus Create');
    }
  }

  /**
   * Check if a Seminar exist and return a boolean.
   * @param \Seminar $seminar
   * @return boolean
   */
  function checkIfSeminarExist($seminar) {
    if ($seminar) {
      return true;
    }
    return false;
  }

  /**
   * 
   * 
   * Return all status of a seminar.
   * @param \Seminar $seminar
   * @return Array
   */
  function getAllStatusBySeminar($seminar) {
    return 
      $this->getDoctrine()
        ->getRepository('SioSemiBundle:Seminar')
        ->getAllStatusBySeminar($seminar);
  }

  /**
   * Persist l'instance d'une entité
   * @param Object $obj tp persist
   */
  function managerPersist($obj) {
    $manager = $this->getDoctrine()->getManager();
    $manager->persist($obj);
    $manager->flush();
  }

  /**
   * Show the register page [?]
   * @param String $mail
   * @param String $clef
   * @param \Status $status
   * @return ?
   */
  function toRegisterPage($mail, $clef, $allStatus) {
    $this->get('session')->getFlashBag()
        ->add('success', 'Veuillez remplir les champs ci-contre pour vous inscrire !');
    $organisations = $this->getDoctrine()
        ->getRepository('SioSemiBundle:Organisation')
        ->findAll();
    $paramOrganisation = $this->getDoctrine()
        ->getRepository('SioSemiBundle:Parameter')
        ->findOneBy(array('clef' => 'organisation'));
    $toview = array('mail' => $mail,
        'clef' => $clef,
        'organisations' => $organisations,
        'paramOrganisation' => $paramOrganisation,
        'allStatus' => $allStatus);
    return $this
            ->render('SioUserBundle:Registration:register.html.twig', $toview);
  }

  /**
   * Start part registration of a new user.
   * @param \Seminar $seminar
   * @param String $mail
   * @param String $clef
   * @param \Status $status
   * @return ?
   */
  function prepareRegister($seminar, $mail, $clef, $allStatus) {
    if (!($this->allowRegistration(
            $seminar->getBeginRegistering(), $seminar->getEndRegistering()))) {
      // Verify the beginRegistering/endRegistering validity of the seminar.
      $return = array("danger", 'La clé d\'inscription est correcte mais semble être expirée, ou alors, le séminaire n\'a pas encore atteint sa date de début d\'inscription ! La date de début d\'inscription est le ' . $seminar->getBeginRegistering()->format("d/m/Y") . ' et la date de fin d\'inscription est le ' . $seminar->getEndRegistering()->format("d/m/Y") . '.');
    } elseif (!preg_match("#^[A-Za-z0-9._-]+@[A-Za-z0-9._-]{2,}\.[a-z]{2,4}$#", $mail)) {
      // The mail is incorrect.
      $return = array("danger", 'L\'email que vous avez entrer semble invalide !');
    } else {
      // Everything seems to be great, but we have to verify if the user already exist.
      $getUser = $this->getDoctrine()->getRepository("SioUserBundle:User")->findOneBy(array("email" => $mail));
      if ($getUser) {
        $return = array("danger", 'Cet utilisateur existe déjà !');
      } else {
        // User doesn't exist.
        return $this->toRegisterPage($mail, $clef, $allStatus);
      }
    }
    // TODO : Flashbag/Redirect.
    $this->get('session')->getFlashBag()->add($return[0], $return[1]);
    return $this->redirect($this->generateUrl(self::ROUTE_LOGIN));
  }

  /**
   * registration of a new user.
   * @param Request $postRequest
   * @return ?
   */
  function doRegisterOrUpdateUserIfOk($postRequest, $seminar) {
    $lastName = $postRequest->get('lastname', NULL);
    $firstName = $postRequest->get('firstname', NULL);
    $pass1 = $postRequest->get('pass1', NULL);
    $pass2 = $postRequest->get('pass2', NULL);
    $mail = $postRequest->get('mail', NULL);
    $mail2 = $postRequest->get('mail2', NULL);
    $organisation = $postRequest->get('organisation', NULL);
    
    
    if ($this->checkIfEquals($mail, $mail2) && $this->checkIfEquals($pass1, $pass2) && $organisation) {
      $firstName = $postRequest->get('firstname', NULL);
      $jobCity = $postRequest->get('adminCity', NULL);
      $homeCity = $postRequest->get('familyCity', NULL);
      $status = $postRequest->get('status', NULL);
      // Verify that the mail is not used.. because the mail can be changed on the view. (For Edition, if the user have a misstake)
      $aUser = $this->getDoctrine()->getRepository('SioUserBundle:User')->findOneBy(array("email" => $mail));
      $user = $this->getUser();
      if (!$aUser) {
        // Register or Update the User & User Seminar.
        $email = (!$aUser || !$user) ? $mail : $user->getEmail(); // key of user
        $newUser = $this->doRegisterUser($email, $lastName, $firstName, $jobCity, $homeCity, $pass1, $organisation);
        $this->registerUserSeminar($seminar, $newUser, $status);

        if ($user) {
          $this->get('session')->getFlashBag()->add('success', 'Votre compte a bien été mis à jour : ' . $firstName );
        }else{ // Auto Login
          $this->get('session')->getFlashBag()->add('success', 'Votre compte a bien été créé, ' . $firstName . ' ! Vous avez été automatiquement connecté(e) à l\'application !');
          $token = new UsernamePasswordToken($newUser, null, 'main', $newUser->getRoles());
          $this->get('security.context')->setToken($token);
        }
      } else {
        $this->get('session')->getFlashBag()->add('warning', 'Cet E-mail est déjà utilisé ! Peut être souhaitiez-vous vous connecter ?');
      }
    } else {
      $this->get('session')->getFlashBag()->add('warning', 'Vos E-mail ou vos mots de passe ne correspondent pas !');
    }
  }

}
