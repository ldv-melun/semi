<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use JMS\SecurityExtraBundle\Annotation\Secure;

class DefaultController extends Controller
{
    /**
     * @Route("/list-seminaire", name="liste_semi")
     * @Template()
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$seminaires = $em->getRepository('SioSemiBundle:Seminaire')->findAll(); 
        return array('seminaires' => $seminaires);
    }
    
    /**
     * @Route("/", name="connect_semi")
     * @Template()
     */
    public function connectSemiAction(Request $request)
    {
        $defaultData = array();
        $form = $this->createFormBuilder($defaultData)
            ->add('cle_semi', 'text')
            ->getForm();

        $form->handleRequest($request);
        if ($request->getMethod() == 'POST') {
            if ($form->isValid()) {
            	$session = $this->get('session');
                $data = $form->getData();
               $exist = $this->getDoctrine()
                    ->getRepository('SioSemiBundle:Seminaire')
					->findOneByCle($form->get('cle_semi')->getData());
                    
                
               if($exist){
               		$session->set('cle',$form->get('cle_semi')->getData());
                   	return $this->redirect($this->generateUrl('connect_user'));
               }else{
               		$session->getFlashBag()->add('notice','Clé non valide !');
                   	return $this->redirect($this->generateUrl('connect_semi'));
					$session->remove('cle');
               }
            }
        }
        return $this->render('SioSemiBundle:Default:connectSemi.html.twig', array(
            'form' => $form->createView(),
        ));
    }
	/**
     * @Route("/connexion", name="connect_user")
     * @Template()
     */
    public function connectUserAction()
    {
    	$logger = $this->get('logger');
		
       if($this->get('session')->has('cle')){
           
                if ($this->get('security.context')
                    ->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                        return $this->redirect($this->generateUrl('gestion_home'));
                }
                $logger->info('1');
                $request = $this->getRequest();
                $session = $request->getSession();
                
                $logger->info($request);
                if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
                  	$logger->info('2');
                    $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
                    $logger->info($request->attributes->get(SecurityContext::AUTHENTICATION_ERROR));
                    $exist = $this->getDoctrine()
                    ->getRepository('SioSemiBundle:Participant')
					->findOneByMail($session->get(SecurityContext::LAST_USERNAME));
                    
                	$logger->info('3 : '.$exist);
                    if($exist){
                   		return $this->render('SioSemiBundle:Default:connectUser.html.twig', array(
                            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                            'error' => $error,
                        ));
                    }else{
                        // renvoi vers pages d'inscription ( deux mail deux mot de passe )
                   		return $this->redirect($this->generateUrl('liste_semi'));
					
                    }
                    
                    
                    
                } else {
                    $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
                    $session->remove(SecurityContext::AUTHENTICATION_ERROR);
                    //return $this->redirect($this->generateUrl('liste_semi'));
                }

       		return $this->render('SioSemiBundle:Default:connectUser.html.twig', array('last_username' => $session->get(SecurityContext::LAST_USERNAME),
                            'error' => $error,));
       }else{
           	return $this->redirect($this->generateUrl('connect_semi'));
           
       }
       
    }
    
    /**
     * @Route("/gestion", name="gestion_home")
     * @Template()
     */
    public function gestionHomeAction()
    {
        $user = $this->getUser();
        if (null === $user) {
            return $this->redirect($this->generateUrl('connect_user'));
        } else {
            return array('user'=> $user);
        }
    	
    }
       
       
       
       
       
       
       
    
}
