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
    $session = $request->getSession();

    // check if clef and mail are in session
    //  => from checkClefAction
    if (!$session->has('seminarClef') || !$session->has('emailUser')) {
      return $this->redirect($this->generateUrl(self::ROUTE_LOGIN));
    }

    $postRequest = $request->request;

    $mail = $session->get('emailUser');
    $clef = $session->get('seminarClef');

    // We will verify the clef now and for all cases.
    $getSeminar = $this->checkClef($clef);

    // We wil get the seminar and all status from that Seminar for all cases.
    $status = $this->getAllStatusForSeminar($getSeminar);

    // Will be used to recognize the case where the user already exist.
    $pass = $postRequest->get('password', NULL);

    if ($pass != NULL) {
      // User already exist. He want to register with another key 
      // or connect with one he already register with.
      $getStatus = $postRequest->get('status', NULL);
      $this->fastRegister($getStatus);
    }

    // Below is obtained only by the classic register page.
    $lastName = $postRequest->get('lastname', NULL);

    if ($lastName == NULL) {
      // User does not exist and doesn't have fill the form
      $this->startRegister($getSeminar, $mail, $clef, $status);
    } else {
      // User does not exist and have fill the form.
      $this->endRegister($postRequest);
    }

    $organisations = $this->getDoctrine()->getRepository('SioSemiBundle:Organisation')->findAll();
    //TODO renommer organisation par typeOrganisation dans parameter
    $typeOrganisation = $this->getDoctrine()->getRepository('SioSemiBundle:Parameter')->findOneBy(array('clef' => 'organisation'));

    $toview = array('mail' => $mail, 'clef' => $clef, 'organisations' => $organisations, 'paramOrganisation' => $typeOrganisation, 'allStatus' => $status);
    return $this->render('SioUserBundle:Registration:register.html.twig', $toview);
  }

  /**
   * Determine if date now inner [$dateStart..$dateEnd]
   * @param \DateTime $dateStart
   * @param \DateTime $dateEnd
   * @return boolean
   */
  function allowRegistration($dateStart, $dateEnd) {
    $now = new \DateTime();
    return $dateStart >= $now && $now <= $dateEnd;

//        if ($dateStart < new \DateTime() && new \DateTime() < $dateEnd) {
//            return true;
//        }
//        return false;
  }

  function checkIfEquals($value1, $value2) {
    if ($value1 == $value2) {
      return true;
    }
    return false;
  }

  function registerUser($mail, $lastName, $firstName, $jobCity, $homeCity, $pass, $organisation) {
    $newUser = new User();
    $newUser->setUsername($mail); // FOSUser say not null (and unique ? so we put mail)
    $newUser->setLastName($lastName);
    $newUser->setFirstName($firstName);
    $newUser->setJobCity($jobCity);
    $newUser->setHomeCity($homeCity);
    $newUser->setEmail($mail);
    $factory = $this->get('security.encoder_factory');
    $encoder = $factory->getEncoder($newUser);
    $password = $encoder->encodePassword($pass, $newUser->getSalt());
    $newUser->setPassword($password);
    $newUser->setRoles(array("ROLE_USER"));
    $newUser->setEnabled(true);

    // Get the Organisation.
    $organisationQuery = $this->getDoctrine()->getRepository('SioSemiBundle:Organisation')->findOneBy(array('id' => $organisation));
    $newUser->setOrganisation($organisationQuery);

    $this->managerPersist($newUser);
    return $newUser;
  }

  function registerUserSeminar($seminar, $status, $mail) {
    $newUserSeminar = new UserSeminar();

    $getStatus = $this->getDoctrine()->getRepository('SioSemiBundle:Status')->findOneBy(array("status" => $status));
    $getUser = $this->getDoctrine()->getRepository('SioUserBundle:User')->findOneBy(array("email" => $mail));
    $newUserSeminar->setSeminar($seminar);
    $newUserSeminar->setStatus($getStatus);
    $newUserSeminar->setUser($getUser);

    $this->managerPersist($newUserSeminar);
  }

  // TODO : à revoir, cette fonction a trop de responsabilités
  function checkClef($clef) {
    $getSeminar = $this->getDoctrine()->getRepository('SioSemiBundle:Seminar')->findOneBy(array('clef' => $clef));
    if (!$getSeminar) {
      $this->get('session')->getFlashBag()->add('danger', 'La clé d\'inscription que vous avez entré est invalide !');
      return $this->redirect($this->generateUrl(self::ROUTE_LOGIN));
    }
    return $getSeminar;
  }

  function getAllStatusForSeminar($seminar) {
    $getSeminarStatus = $this->getDoctrine()->getRepository("SioSemiBundle:SeminarStatus")->findBy(array("seminar" => $seminar->getId()));
    foreach ($getSeminarStatus as $seminarStatus) {
      $status[] = $this->getDoctrine()->getRepository("SioSemiBundle:Status")->findBy(array("id" => $seminarStatus->getStatus()));
    }
    return $status;
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

  function showRegisterPage($mail, $clef, $status) {
    $this->get('session')->getFlashBag()->add('success', 'Veuillez remplir les champs ci-contre pour vous inscrire !');
    $organisations = $this->getDoctrine()->getRepository('SioSemiBundle:Organisation')->findAll();
    $paramOrganisation = $this->getDoctrine()->getRepository('SioSemiBundle:Parameter')->findOneBy(array('clef' => 'organisation'));
    $toview = array('mail' => $mail, 'clef' => $clef, 'organisations' => $organisations, 'paramOrganisation' => $paramOrganisation, 'allStatus' => $status);
    return $this->render('SioUserBundle:Registration:register.html.twig', $toview);
  }

  // Case : User exist, new UserSeminar to persist.
  function fastRegister($status) {
    if ($status == NULL) {
      // The user just came from the index.
      $toview = array("mail" => $mail, "clef" => $clef, "fastRegister" => true, 'allStatus' => $status);
      return $this->render('SioUserBundle:Registration:register.html.twig', $toview);
    } else {
      // TODO : The user will now try to register a new UserSeminar Entity.
    }
  }

  function startRegister($seminar, $mail, $clef, $status) {
    if (!($this->allowRegistration($seminar->getBeginRegistering(), $seminar->getEndRegistering()))) {
      // Verify the beginRegistering/endRegistering validity of the seminar.
      $return = array("danger", 'La clé d\'inscription est correcte mais semble être expiré, ou alors, le séminaire n\'a pas encore atteint sa date de début d\'inscription ! La date de début d\'inscription est le ' . $seminar->getBeginRegistering()->format("d/m/Y") . ' et la date de fin d\'inscription est le ' . $seminar->getEndRegistering()->format("d/m/Y") . '.');
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
        return $this->showRegisterPage($mail, $clef, $status);
      }
    }
    // TODO : Flashbag/Redirect.
    $this->get('session')->getFlashBag()->add($return[0], $return[1]);
    return $this->redirect($this->generateUrl(self::ROUTE_LOGIN));
  }

  function endRegister($postRequest) {

    $firstName = $postRequest->get('firstname', NULL);
    $pass1 = $postRequest->get('pass1', NULL);
    $pass2 = $postRequest->get('pass2', NULL);
    $mail = $postRequest->get('mail', NULL);
    $mail2 = $postRequest->get('mail2', NULL);
    if ($this->checkIfEquals($mail, $mail2) && $this->checkIfEquals($pass1, $pass2)) {
      $firstName = $postRequest->get('firstname', NULL);
      $organisation = $postRequest->get('organisation', NULL);
      $jobCity = $postRequest->get('adminCity', NULL);
      $homeCity = $postRequest->get('familyCity', NULL);

      // Verify that the mail is not used.. because the mail can be changed on the view. (For Edition, if the user have a misstake)
      $aUser = $this->getDoctrine()->getRepository('SioUserBundle:User')->findOneBy(array("email" => $mail));
      if (!$aUser) {
        // Register the new User & User Seminar.
        $newUser = $this->registerUser($mail, $lastName, $firstName, $jobCity, $homeCity, $pass1, $organisation);
        $this->registerUserSeminar($seminar, $status, $mail);

        // Auto Login.
        $this->get('session')->getFlashBag()->add('success', 'Votre compte a bien été créé, ' . $firstName . ' ! Vous avez été automatiquement connecté(e) à l\'application !');
        $token = new UsernamePasswordToken($newUser, null, 'main', $newUser->getRoles());
        $this->get('security.context')->setToken($token);
        // TODO:  seminarClef is in session, so user can go direct to seminar
        return $this->redirect($this->generateUrl('_semi_user_index'));
      } else {
        $this->get('session')->getFlashBag()->add('warning', 'Cet E-mail est déjà utilisé ! Peut être souhaitiez-vous vous connecter ?');
      }
    } else {
      $this->get('session')->getFlashBag()->add('warning', 'Vos E-mail ou vos mots de passe ne correspondent pas !');
    }
  }

}
