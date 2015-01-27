<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sio\SemiBundle\Entity\User as User;

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
        
        // Can be obtained from main page or register page.
        $mail = $request->get('mail', NULL);
        $key = $request->get('key', NULL);
        
        // Below is obtained only by the register page.
        $lastName = $request->get('lastname', NULL);
        
        // First, we verify that the lastname isn't null : if it is, the user came from the main page.
        if($lastName != NULL)
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
            $mail1 = $request->get('mail1', NULL);
            $mail2 = $request->get('mail2', NULL);
            
            // The first thing we have to verify is that the 2 passwords and the 2 mails are equals.
            if(checkIfEquals($mail1, $mail2) && checkIfEquals($pass1, $pass2))
            {
                $newUser = new User();
                $newUser->setLastName($lastName);
                $newUser->setFirstName($firstName);
                $newUser->setJobCity($jobCity);
                $newUser->setHomeCity($homeCity);
                $newUser->setMail($mail1);
                $newUser->setPassword(password_hash($pass1, PASSWORD_BCRYPT, array('cost' => 12)));
                $newUser->setRoles("ROLE_USER");
                
                // Get the Organisation.
                $organisationQuery = $this->getDoctrine()->getRepository('SioSemiBundle:Organisation')->findOneBy(array('id' => $organisation));
                $newUser->setOrganisation($organisationQuery);
                
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($newUser);
                $manager->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'Votre compte a bien été créé, '.$firstName.' ! Vous pouvez dès à présent vous connecter à l\'application !');
                $key = NULL;
            }
            else
            {
                $this->get('session')->getFlashBag()->add('warning', 'Vos E-mail ou vos mots de passe ne correspondent pas !');
            }
        }
        else
        {
            // User came from main page. We can verify the key now.
            $getAllKey = $this->getDoctrine()->getRepository('SioSemiBundle:Seminar')->findOneBy(array('key' => $key));
            if(!$getAllKey && $key != NULL)
            {
                // Case where the key is incorrect.
                $this->get('session')->getFlashBag()->add('warning', 'La clé d\'inscription que vous avez entré est invalide !');
                $key = NULL;
            }
        }
        
        $organisations = $this->getDoctrine()->getRepository('SioSemiBundle:Organisation')->findAll();
        $paramOrganisation = $this->getDoctrine()->getRepository('SioSemiBundle:Parameter')->findOneBy(array('clef' => 'organisation'));
        return array('mail' => $mail, 'key' => $key, 'organisations' => $organisations, 'paramOrganisation' => $paramOrganisation);
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