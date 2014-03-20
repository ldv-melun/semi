<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * 
 */
class ParticipantController extends Controller
{
	/**
	 * @Route("/gestion/profil", name="gestion_profil")
	 * @Template()
	 */
	public function gestionProfilAction()
	{
                $userCurrent = $this->getUser();
                $form = $this->createFormBuilder($userCurrent)
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
                        $em->persist($userCurrent);
                        $em->flush();

                        //return $this->redirect();
                    }
                }
		return array("form" => $form->createView());
	}
	/**
	 * @Route("/gestion/a-propos", name="about_gestion")
	 * @Template()
	 */
	public function aboutAction()
	{
            return $this->render('SioSemiBundle:Default:about.html.twig', array());
        }
        
        /**
	 * @Route("/gestion/supprimer", name="supprimer_profil_gestion")
	 * @Template()
	 */
	public function supprimerProfilAction()
	{
            
            $em = $this->getDoctrine()->getManager();
           //$u =  $em->getRepository('SioSemiBundle:Participant')->findById($this->getUser()->getId());
            $userCurrent = $this->getUser();
            
            $em->remove($userCurrent);
            $em->flush();
            return $this->redirect($this->generateUrl('connect_semi'));
        }
}
