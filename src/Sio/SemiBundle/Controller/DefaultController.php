<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sio\SemiBundle\Entity\Participant;

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
                /* verification de l'ouverture du seminaire */
                $seminaire = $this->getDoctrine()
                                    ->getRepository('SioSemiBundle:Seminaire')
                                    ->findOneByCle($this->get('session')->get('cle'));
                $now = new \DateTime();
                $diff = $seminaire->getDateFin()->diff($now);
                $semi_active = (($diff->format('%s') > 0)?true:false);
           
           
           
                if ($this->get('security.context')
                    ->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                        return $this->redirect($this->generateUrl('gestion_home'));
                }
                
                $request = $this->getRequest();
                $session = $request->getSession();
                
                $logger->info($request);
                $logger->info('2'.$session->get(SecurityContext::LAST_USERNAME));
                $logger->info('tag'.$session->get(SecurityContext::AUTHENTICATION_ERROR));
                if ($session->get(SecurityContext::AUTHENTICATION_ERROR)) {
                    $logger->info('3');
                    $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
                    $logger->info($request->attributes->get(SecurityContext::AUTHENTICATION_ERROR));
                    $exist = $this->getDoctrine()
                    ->getRepository('SioSemiBundle:Participant')
					->findOneByMail($session->get(SecurityContext::LAST_USERNAME));
                    
                    if($exist){
                                return $this->render('SioSemiBundle:Default:connectUser.html.twig', array(
                            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                            'error' => $error,
                            'semi_active' => $semi_active,
                        ));
                    }else{
                        // renvoi vers pages d'inscription ( deux mail deux mot de passe )
                        $session->set('email', $session->get(SecurityContext::LAST_USERNAME) );
                        $session->remove(SecurityContext::AUTHENTICATION_ERROR);
                        $session->remove(SecurityContext::LAST_USERNAME);
                   	return $this->redirect($this->generateUrl('create_account'));
					
                    }
                    
                    
                    
                } else {
                    $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
                    $session->remove(SecurityContext::AUTHENTICATION_ERROR);
                    $logger->info('5'.$session->get(SecurityContext::AUTHENTICATION_ERROR));
                    //return $this->redirect($this->generateUrl('liste_semi'));
                }
                      
                

       		return $this->render('SioSemiBundle:Default:connectUser.html.twig', array('last_username' => $session->get(SecurityContext::LAST_USERNAME),
                            'error' => $error,
                            'semi_active' => $semi_active,));
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
    /**
     * @Route("/a-propos", name="about")
     * @Template()
     */
    public function aboutAction()
    {     
            return array();	
    }
    
    /**
     * @Route("/creation-compte", name="create_account")
     * @Template()
     */
    public function creaAccountAction(Request $request)
    {     
            $session = $this->getRequest()->getSession();
            if($session->get('email')){
                $defaultData = array();
                $form = $this->createFormBuilder($defaultData)
                    ->add('mail', 'text', array(
                                    'label' => 'Mail',
                                    'data' => $session->get('email'),
                                    'read_only' => true,
                          ))
                    ->add('password', 'password', array('label'=>'Mot de passe'))
                    ->add('repassword', 'password', array('label'=>'Veuillez retaper votre mot de passe'))
                    ->getForm();

                $form->handleRequest($request);
                if ($request->getMethod() == 'POST') {
                    if ($form->isValid()) {
                        if($form->get('password')->getData() === $form->get('repassword')->getData()){
                            $session->set('valid', 'on');
                            $session->set('pass',$form->get('password')->getData());
                            return $this->redirect($this->generateUrl('renseignement_new_account'));
                        }else{
                            $erreur = 'Les mots de passe ne correspondent pas';
                            return array(
                                'form' => $form->createView(),
                                'erreur' => $erreur,
                            );
                        }
                    }
                 }
                 return array(
                     'form' => $form->createView(),
                 );
            }
            
            return $this->redirect($this->generateUrl('connect_user'));
    }
    
    /**
     * @Route("/creation-compte-renseignement", name="renseignement_new_account")
     * @Template()
     */
    public function infNewAccountAction()
    {     
        if($this->get('session')->has('valid')){
           $participant = new Participant();
           $participant->setMail($this->get('session')->get('email'))
                   ->setPassword($this->get('session')->get('pass'))
                   ->setRoles(array('ROLE_PARTICIPANT'));
           $form = $this->createFormBuilder($participant)
               ->add('nom', 'text')
               ->add('prenom', 'text')
               ->add('mail', 'text',array(
                   'read_only' => true,
               ))
               ->add('academie', 'entity', array(
                   'class'    => 'SioSemiBundle:Academie',
                   'property' => 'nom',
                   'multiple' => false,
                   'expanded' => false)
               )
               ->add('resFamiliale', 'text')
               ->add('resAdministrative', 'text')                    
               ->add('titre', 'choice', array(
                   'choices'   => array(
                       'professeur'   => 'Professeur',
                       'ipr' => 'IA-IPR',
                       'ien'   => 'IEN',
                       'autre'   => 'Autre',
                   ),
                   'multiple'  => false,
                   'expanded' => true,
               ))
               ->getForm();

               $request = $this->get('request');
               // On vérifie qu'elle est de type POST
               if ($request->getMethod() == 'POST') {

                   $form->bind($request);

                   if ($form->isValid()) {

                       $em = $this->getDoctrine()->getManager();
                       $participant->setSalt("");
                       $participant->setDateCrea(new \DateTime());
                       $em->persist($participant);
                       $em->flush();
                       $this->get('session')->remove('email');
                       $this->get('session')->remove('pass');
                       $this->get('session')->remove('valid');
                       //return $this->redirect();
                       return $this->redirect($this->generateUrl('connect_user'));
                   }
               }
             return array('form'=>$form->createView());
        }
        return $this->redirect($this->generateUrl('connect_user'));
    	
    }
       
       
       
       
       
       
       
    
}
