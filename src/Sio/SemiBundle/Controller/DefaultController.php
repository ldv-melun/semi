<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sio\SemiBundle\Entity\User as User;
use Sio\SemiBundle\Entity\UserSeminar as UserSeminar;

class DefaultController extends Controller
{
    
    /**
     * @Route("/", name="_semi_default_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
    
    /**
     * @Route("/redirect", name="_semi_default_redirect")
     * @Template()
     */
    public function redirectAction()
    {
        // Redirect the user. In this route we will also update ipLastLogin & dateLastLogin.
        $response = new Response();
        $response->setStatusCode(200);
        
        if($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            // ipLastLogin & dateLastLogin
            $user = $this->getUser();
            $user->setIpLastLogin($this->container->get('request')->getClientIp());
            $user->setDateLastLogin(new \DateTime('now'));
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
            
            if(true === $this->get('security.context')->isGranted('ROLE_ADMIN'))
            {
                $response->headers->set('Refresh', '5; url='.$this->generateUrl('_semi_admin_index'));
                $response->send();

                return array('firstName' => $user->getFirstName(), 'role' => $this->generateUrl('_semi_admin_index'));
            }
            elseif(true === $this->get('security.context')->isGranted('ROLE_MANAGER'))
            {
                $response->headers->set('Refresh', '5; url='.$this->generateUrl('_semi_manager_index'));
                $response->send();

                return array('firstName' => $user->getFirstName(), 'role' => $this->generateUrl('_semi_manager_index'));
            }
            elseif(true === $this->get('security.context')->isGranted('ROLE_USER'))
            {
                $response->headers->set('Refresh', '5; url='.$this->generateUrl('_semi_user_index'));
                $response->send();

                return array('firstName' => $user->getFirstName(), 'role' => $this->generateUrl('_semi_user_index'));
            }
        }
        else
        {
            // Access trough ROLE_ANONYMOUS.
            return $this->redirect($this->generateUrl('_semi_default_index'));
        }
    }
    
    /**
     * @Route("/register", name="_semi_default_register")
     * @Template()
     */
    public function registerAction()
    {
        $request = Request::createFromGlobals();
        
        $mail = $request->get('mail', NULL);
        $clef = $request->get('clef', NULL);
        
        // Below is obtained only by the register page.
        $lastName = $request->get('lastname', NULL);
        
        // First, we verify that the lastname isn't null : if it is, the user came from the main page.
        if($lastName == NULL)
        {
            if($mail == NULL || $clef == NULL)
            {
                $this->get('session')->getFlashBag()->add('danger', 'Vous devez rentrer une clé et votre E-mail pour pouvoir vous inscrire !');
                return $this->redirect($this->generateUrl('_semi_default_index'));
            }
            else
            {
                // Verify the key.
                $getSeminar = $this->getDoctrine()->getRepository('SioSemiBundle:Seminar')->findOneBy(array('clef' => $clef));
                if(!$getSeminar)
                {
                    // Case where the clef is incorrect.
                    $this->get('session')->getFlashBag()->add('danger', 'La clé d\'inscription que vous avez entré est invalide !');
                    return $this->redirect($this->generateUrl('_semi_default_index'));
                }
                elseif(!($getSeminar->getBeginRegistering() > new \DateTime() || new \DateTime() < $getSeminar->getEndRegistering()))
                {
                    // Verify the beginRegistering/endRegistering validity of the seminar.
                    $this->get('session')->getFlashBag()->add('danger', 'La clé d\'inscription est correcte mais semble être expiré, ou alors, le séminaire n\'a pas encore atteint sa date de début d\'inscription ! La date de début d\'inscription est le '.$getSeminar->getBeginRegistering()->format("d/m/Y").' et la date de fin d\'inscription est le '.$getSeminar->getEndRegistering()->format("d/m/Y").'.');
                    return $this->redirect($this->generateUrl('_semi_default_index'));
                }
                elseif(!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $mail))
                {
                    // The mail is incorrect.
                    $this->get('session')->getFlashBag()->add('danger', 'L\'email que vous avez entrer semble invalide !');
                    return $this->redirect($this->generateUrl('_semi_default_index'));
                }
                else
                {
                    // Everything seems to be great, but we have to verify if the user already exist.
                    $getUser = $this->getDoctrine()->getRepository("SioSemiBundle:User")->findOneBy(array("mail" => $mail));
                    if($getUser)
                    {
                        // User exist.
                        $this->get('session')->getFlashBag()->add('danger', 'User exist !');
                        return $this->redirect($this->generateUrl('_semi_default_index'));
                    }
                    else
                    {
                        // User doesn't exist. We have to get the different status of the seminar.
                        $this->get('session')->getFlashBag()->add('success', 'Veuillez remplir les champs ci-contre pour vous inscrire !');
                        $getSeminarStatus = $this->getDoctrine()->getRepository("SioSemiBundle:SeminarStatus")->findBy(array("seminar" => $getSeminar->getId()));
                        foreach($getSeminarStatus as $seminarStatus)
                        {
                            $status[] = $this->getDoctrine()->getRepository("SioSemiBundle:Status")->findBy(array("id" => $seminarStatus->getStatus()));
                        }

                        $organisations = $this->getDoctrine()->getRepository('SioSemiBundle:Organisation')->findAll();
                        $paramOrganisation = $this->getDoctrine()->getRepository('SioSemiBundle:Parameter')->findOneBy(array('clef' => 'organisation'));
                        return array('mail' => $mail, 'clef' => $clef, 'organisations' => $organisations, 'paramOrganisation' => $paramOrganisation, 'allStatus' => $status);
                    }
                }
            }
        }
        else
        {
            
            // TODO : Fonctions à externaliser.
            function checkIfEquals($value1, $value2)
            {
                if($value1 == $value2)
                {
                    return true;
                }
                return false;
            }
            // TODO : Fonctions à externaliser.
            
            // User came from register page. We can get the other values and try to register him.
            $firstName = $request->get('firstname', NULL);
            $pass1 = $request->get('pass1', NULL);
            $pass2 = $request->get('pass2', NULL);
            $organisation = $request->get('organisation', NULL);
            $jobCity = $request->get('adminCity', NULL);
            $homeCity = $request->get('familyCity', NULL);
            $mail = $request->get('mail', NULL);
            $mail2 = $request->get('mail2', NULL);
            $getStatus = $request->get('status', NULL);
            $getSeminar = $this->getDoctrine()->getRepository('SioSemiBundle:Seminar')->findOneBy(array('clef' => $clef));
            
            // The first thing we have to verify is that the 2 passwords and the 2 mails are equals.
            if(checkIfEquals($mail, $mail2) && checkIfEquals($pass1, $pass2))
            {
                // Verify that the mail is not used.. because the mail can be changed on the view. (For Edition, if the user have a misstake)
                $getUser = $this->getDoctrine()->getRepository('SioSemiBundle:User')->findOneBy(array("mail" => $mail));
                if(!$getUser)
                {
                    $newUser = new User();
                    $newUser->setLastName($lastName);
                    $newUser->setFirstName($firstName);
                    $newUser->setJobCity($jobCity);
                    $newUser->setHomeCity($homeCity);
                    $newUser->setMail($mail);
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($newUser);
                    $password = $encoder->encodePassword($pass1, $newUser->getSalt());
                    $newUser->setPassword($password);
                    $newUser->setRoles("ROLE_USER");

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
                    $getUser = $this->getDoctrine()->getRepository('SioSemiBundle:User')->findOneBy(array("mail" => $mail));
                    $newUserSeminar->setSeminar($getSeminar);
                    $newUserSeminar->setStatus($getStatus);
                    $newUserSeminar->setUser($getUser);

                    $manager->persist($newUserSeminar);
                    $manager->flush();

                    $this->get('session')->getFlashBag()->add('success', 'Votre compte a bien été créé, '.$firstName.' ! Vous pouvez dès à présent vous connecter à l\'application !');
                    return $this->redirect($this->generateUrl('_semi_default_login'));
                }
                else
                {
                    $this->get('session')->getFlashBag()->add('warning', 'Cet E-mail est déjà utilisé ! Peut être souhaitiez-vous vous connecter ?');
                }
            }
            else
            {
                $this->get('session')->getFlashBag()->add('warning', 'Vos E-mail ou vos mots de passe ne correspondent pas !');
            }
        }
        
        $getSeminarStatus = $this->getDoctrine()->getRepository("SioSemiBundle:SeminarStatus")->findBy(array("seminar" => $getSeminar->getId()));
        foreach($getSeminarStatus as $seminarStatus)
        {
            $status[] = $this->getDoctrine()->getRepository("SioSemiBundle:Status")->findBy(array("id" => $seminarStatus->getStatus()));
        }
        
        $organisations = $this->getDoctrine()->getRepository('SioSemiBundle:Organisation')->findAll();
        $paramOrganisation = $this->getDoctrine()->getRepository('SioSemiBundle:Parameter')->findOneBy(array('clef' => 'organisation'));
        return array('mail' => $mail, 'clef' => $clef, 'organisations' => $organisations, 'paramOrganisation' => $paramOrganisation, 'allStatus' => $status);
    }
    
    /**
     * @Route("/login", name="_semi_default_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
          return $this->redirect($this->generateUrl('_semi_default_index'));
        }

        $session = $request->getSession();

        // On vérifie s'il y a des erreurs d'une précédente soumission du formulaire
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
        {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        else
        {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('SioSemiBundle:Default:login.html.twig', array(
          // Valeur du précédent nom d'utilisateur entré par l'internaute
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
}