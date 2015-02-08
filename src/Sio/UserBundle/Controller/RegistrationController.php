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

    $pass = $postRequest->get('password', NULL); 
        // Will be used to recognize the case where the user already exist.
        // [FROM INDEX]

    if ($pass != NULL) {
      // CASE WHERE THE USER IS ALREADY REGISTERED. 
      // We will ignore the rest of the code below.
      $getStatus = $postRequest->get('status', NULL);
      if ($getStatus == NULL) {
        // The user just came from the index.
        $getSeminar = $this->getDoctrine()->getRepository('SioSemiBundle:Seminar')->findOneBy(array('clef' => $clef));
        $getSeminarStatus = $this->getDoctrine()->getRepository("SioSemiBundle:SeminarStatus")->findBy(array("seminar" => $getSeminar->getId()));
        foreach ($getSeminarStatus as $seminarStatus) {
          $status[] = $this->getDoctrine()->getRepository("SioSemiBundle:Status")->findBy(array("id" => $seminarStatus->getStatus()));
        }
        $toview = array("mail" => $mail, "clef" => $clef, "fastRegister" => true, 'allStatus' => $status);
        return $this->render('SioUserBundle:Registration:register.html.twig', $toview);
      } else {
        // The user will now try to register a now UserSeminar Entity.
      }
    }

    // Below is obtained only by the register page.
    $lastName = $postRequest->get('lastname', NULL);

    // First, we verify that the lastname isn't null : if it is, the user came from the main page.
    if ($lastName == NULL) {
      if ($mail == NULL || $clef == NULL) {
        $this->get('session')->getFlashBag()->add('danger', 'Vous devez rentrer une clé et votre E-mail pour pouvoir vous inscrire !');
        return $this->redirect($this->generateUrl(self::ROUTE_LOGIN));
      } else {
        // Verify the key.
        $getSeminar = $this->getDoctrine()->getRepository('SioSemiBundle:Seminar')->findOneBy(array('clef' => $clef));
        if (!$getSeminar) {
          // Case where the clef is incorrect.
          $this->get('session')->getFlashBag()->add('danger', 'La clé d\'inscription que vous avez entré est invalide !');
          return $this->redirect($this->generateUrl(self::ROUTE_LOGIN));
        } elseif (!($getSeminar->getBeginRegistering() > new \DateTime() || new \DateTime() < $getSeminar->getEndRegistering())) {
          // Verify the beginRegistering/endRegistering validity of the seminar.
          $this->get('session')->getFlashBag()->add('danger', 'La clé d\'inscription est correcte mais semble être expiré, ou alors, le séminaire n\'a pas encore atteint sa date de début d\'inscription ! La date de début d\'inscription est le ' . $getSeminar->getBeginRegistering()->format("d/m/Y") . ' et la date de fin d\'inscription est le ' . $getSeminar->getEndRegistering()->format("d/m/Y") . '.');
          return $this->redirect($this->generateUrl(self::ROUTE_LOGIN));
        } elseif (!preg_match("#^[A-Za-z0-9._-]+@[A-Za-z0-9._-]{2,}\.[a-z]{2,4}$#", $mail)) {
          // The mail is incorrect.
          $this->get('session')->getFlashBag()->add('danger', 'L\'email que vous avez entrer semble invalide !');
          return $this->redirect($this->generateUrl(self::ROUTE_LOGIN));
        } else {
          // Everything seems to be great, but we have to verify if the user already exist.
          $getUser = $this->getDoctrine()->getRepository("SioUserBundle:User")->findOneBy(array("email" => $mail));
          if ($getUser) {
            // User exist.
            $this->get('session')->getFlashBag()->add('danger', 'Cet utilisateur existe déjà !');
            return $this->redirect($this->generateUrl(self::ROUTE_LOGIN));
          } else {
            // User doesn't exist. We have to get the different status of the seminar.
            $this->get('session')->getFlashBag()->add('success', 'Veuillez remplir les champs ci-contre pour vous inscrire !');
            $getSeminarStatus = $this->getDoctrine()->getRepository("SioSemiBundle:SeminarStatus")->findBy(array("seminar" => $getSeminar->getId()));
            foreach ($getSeminarStatus as $seminarStatus) {
              $status[] = $this->getDoctrine()->getRepository("SioSemiBundle:Status")->findBy(array("id" => $seminarStatus->getStatus()));
            }

            $organisations = $this->getDoctrine()->getRepository('SioSemiBundle:Organisation')->findAll();
            $paramOrganisation = $this->getDoctrine()->getRepository('SioSemiBundle:Parameter')->findOneBy(array('clef' => 'organisation'));
            $toview = array('mail' => $mail, 'clef' => $clef, 'organisations' => $organisations, 'paramOrganisation' => $paramOrganisation, 'allStatus' => $status);
            return $this->render('SioUserBundle:Registration:register.html.twig', $toview);
          }
        }
      }
    } else {

      // TODO : Fonctions à externaliser.
      function checkIfEquals($value1, $value2) {
        if ($value1 == $value2) {
          return true;
        }
        return false;
      }

      // TODO : Fonctions à externaliser.
      // User came from register page. We can get the other values and try to register him.
      $firstName = $postRequest->get('firstname', NULL);
      $pass1 = $postRequest->get('pass1', NULL);
      $pass2 = $postRequest->get('pass2', NULL);
      $organisation = $postRequest->get('organisation', NULL);
      $jobCity = $postRequest->get('adminCity', NULL);
      $homeCity = $postRequest->get('familyCity', NULL);
      $mail = $postRequest->get('mail', NULL);
      $mail2 = $postRequest->get('mail2', NULL);
      $getStatus = $postRequest->get('status', NULL);
      $getSeminar = $this->getDoctrine()->getRepository('SioSemiBundle:Seminar')->findOneBy(array('clef' => $clef));

      // The first thing we have to verify is that the 2 passwords and the 2 mails are equals.
      if (checkIfEquals($mail, $mail2) && checkIfEquals($pass1, $pass2)) {
        // Verify that the mail is not used.. because the mail can be changed on the view. (For Edition, if the user have a misstake)
        $getUser = $this->getDoctrine()->getRepository('SioUserBundle:User')->findOneBy(array("email" => $mail));
        if (!$getUser) {
          $newUser = new User();
          $newUser->setUsername($mail); // FOSUser say not null (and unique ? so we put mail)
          $newUser->setLastName($lastName);
          $newUser->setFirstName($firstName);
          $newUser->setJobCity($jobCity);
          $newUser->setHomeCity($homeCity);
          $newUser->setEmail($mail);
          $factory = $this->get('security.encoder_factory');
          $encoder = $factory->getEncoder($newUser);
          $password = $encoder->encodePassword($pass1, $newUser->getSalt());
          $newUser->setPassword($password);
          $newUser->setRoles(array("ROLE_USER"));
          $newUser->setEnabled(true);
          // Get the Organisation.
          $organisationQuery = $this->getDoctrine()->getRepository('SioSemiBundle:Organisation')->findOneBy(array('id' => $organisation));
          $newUser->setOrganisation($organisationQuery);

          $manager = $this->getDoctrine()->getManager();
          $manager->persist($newUser);
          $manager->flush();

          // Done for entity User. But we have to create a UserSeminar now.
          $newUserSeminar = new UserSeminar();

          $getSeminarStatus = $this->getDoctrine()->getRepository('SioSemiBundle:SeminarStatus')->findOneBy(array("seminar" => $getSeminar->getId()));
          $getStatus = $this->getDoctrine()->getRepository('SioSemiBundle:Status')->findOneBy(array("status" => $getStatus));
          $getUser = $this->getDoctrine()->getRepository('SioUserBundle:User')->findOneBy(array("email" => $mail));
          $newUserSeminar->setSeminar($getSeminar);
          $newUserSeminar->setStatus($getStatus);
          $newUserSeminar->setUser($getUser);

          $manager->persist($newUserSeminar);
          $manager->flush();

          $this->get('session')->getFlashBag()->add('success', 'Votre compte a bien été créé, ' . $firstName . ' ! Vous pouvez dès à présent vous connecter à l\'application !');

          //auto login 
          $token = new UsernamePasswordToken($newUser, null, 'main', $newUser->getRoles());
          $this->get('security.context')->setToken($token);
          // now go to seminar 
          // TODO:  seminarClef is in session, so user can go direct to seminar
          return $this->redirect($this->generateUrl('_semi_user_index'));
          
        } else {
          $this->get('session')->getFlashBag()->add('warning', 'Cet E-mail est déjà utilisé ! Peut être souhaitiez-vous vous connecter ?');
        }
      } else {
        $this->get('session')->getFlashBag()->add('warning', 'Vos E-mail ou vos mots de passe ne correspondent pas !');
      }
    }

    $getSeminarStatus = $this->getDoctrine()->getRepository("SioSemiBundle:SeminarStatus")->findBy(array("seminar" => $getSeminar->getId()));
    foreach ($getSeminarStatus as $seminarStatus) {
      $status[] = $this->getDoctrine()->getRepository("SioSemiBundle:Status")->findBy(array("id" => $seminarStatus->getStatus()));
    }

    $organisations = $this->getDoctrine()->getRepository('SioSemiBundle:Organisation')->findAll();
    $paramOrganisation = $this->getDoctrine()->getRepository('SioSemiBundle:Parameter')->findOneBy(array('clef' => 'organisation'));
    
    $toview = array('mail' => $mail, 'clef' => $clef, 'organisations' => $organisations, 'paramOrganisation' => $paramOrganisation, 'allStatus' => $status);
    return $this->render('SioUserBundle:Registration:register.html.twig', $toview);
  }

}
