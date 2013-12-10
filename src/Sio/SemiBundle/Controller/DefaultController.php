<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
            ->add('go','submit')
            ->getForm();

        $form->handleRequest($request);
        if ($request->getMethod() == 'POST') {
            if ($form->isValid()) {
            	$session = $this->get('session');
                $data = $form->getData();
                // TODO traitement pour savoir si la clé existe
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
       if($this->get('session')->has('cle')){
       		return $this->render('SioSemiBundle:Default:connectUser.html.twig', array());
       }else{
           	return $this->redirect($this->generateUrl('connect_semi'));
       }
    }
}
