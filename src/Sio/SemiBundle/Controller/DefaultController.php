<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
Use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    const ROUTE_LOGIN = 'fos_user_security_login';

    /**
     * @Route("/", name="_semi_default_index")
     * @Template()
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl(self::ROUTE_LOGIN));
    }


    /**
     * @return JSon 
     *  {clefOk: true|false, userKnown: true|false, emailSyntaxOk: true|false}
     * @Route("/checkclef", name="_semi_default_check_clef")
     */
    public function checkClefAndEmailAction(Request $request)
    {
       $seminarClef = $request->request->get('seminar-clef', null);
       $emailUser = $request->request->get('email-user', null);
      
       if ($seminarClef) {
          $seminar = $this->getDoctrine()
               ->getRepository('SioSemiBundle:Seminar')
               ->findOneBy(array('clef' => $seminarClef));
       } else {
          $seminar = null;
       }
       $emailSyntax = filter_var($emailUser, FILTER_VALIDATE_EMAIL);
       if ($emailUser && $emailSyntax) {
          $semiUser = $this->getDoctrine()
              ->getRepository('SioUserBundle:User')
              ->findOneBy(array('email' => $emailUser));
       } else {
          $semiUser = null;
       }
              
       // put clef into session 
       if ($seminar) {
           $request->getSession()->set('seminarClef', $seminarClef);
       }
       // put email into session for register
       if ($emailSyntax) {
           $request->getSession()->set('emailUser', $emailUser);
       }
       
       $data = array('clefOk'=>$seminar != false, 
                     'userKnown' => $semiUser != false, 
                     'emailSyntaxOk'=> $emailSyntax == true);
      
       return new JsonResponse($data);        
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
     * @Route("/registerajax", name="_semi_default_registerajax")
     * @Template()
     */
    /*
    public function registerAjaxAction(Request $request)
    {
     * 
     */
        /*
         * Jquery SEMI
         * 5 Statements available for [action] :
         * add => Add an input. ("password" is a valid argument)
         * redirect => Redirect the user. ("register" is a valid argument (WITH POST !), also, you can put an URL)
         * error => Show an error.
         * success => Show a success.
         * warning => Show a warning.
         * 
         * Structure is :
         * action|argument
         * 
         * That means that you can send more than one command to Jquery. Ex :
         * add|password|warning|You must login to do this action !
         */
       /* 
        $mail = $request->get('mail', NULL);
        $clef = $request->get('clef', NULL);
        $pass = $request->get('password', NULL);
        if($mail != NULL && $clef != NULL && $pass != NULL)
        {
            // Receive a password. Checking for the user. We will also verify the clef again.
            $getUser = $this->getDoctrine()->getRepository('SioSemiBundle:User')->findOneBy(array("mail" => $mail, "password" => $pass));
            $getClef = $this->getDoctrine()->getRepository('SioSemiBundle:Seminar')->findOneBy(array("clef" => $clef));
            if($getClef)
            {
                if($getUser)
                {
                    return array("return" => "redirect|fastRegister");
                }
                else
                {
                    return array("return" => "error|Votre mot de passe ou votre E-mail ne semble pas bon.");
                }
            }
            else
            {
                return array("return" => "error|La clé semble invalide !");
            }
        }
        elseif($mail != NULL && $clef != NULL && $pass == NULL)
        {
            // Verify that the user exist.
            $getMail = $this->getDoctrine()->getRepository('SioSemiBundle:User')->findOneBy(array("mail" => $mail));
            $getClef = $this->getDoctrine()->getRepository('SioSemiBundle:Seminar')->findOneBy(array("clef" => $clef));
            if($getMail)
            {
                // User exist. We have to verify the clef first.
                if($getClef)
                {
                    // The key is correct. We have to register the new UserSeminar. For that, we must show the password field.
                    return array("return" => "add|password|warning|La clé est valide, mais ce compte existe déjà. Veuillez taper votre mot de passe pour continuer.");
                }
                else
                {
                    // The key is not correct. It is not usefull to show the password field.
                    return array("return" => "error|Votre clé d'inscription semble invalide.");
                }
            }
            else
            {
                // User does not exist. We have to verify the clef.
                if($getClef)
                {
                    return array("return" => "redirect|register");
                }
                else
                {
                    return array("return" => "error|Votre clé d'inscription semble invalide.");
                }
            }
        }
        else
        {
            return array("return" => "error|Veuillez remplir les champs mail et clé !");
        }
    }
    */
    
    /**
     * @Route("/login2", name="self::ROUTE_LOGIN")
     * @Template()
     */
    /*
    public function loginAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
          return $this->redirect($this->generateUrl(self::ROUTE_LOGIN));
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
      */
     
}