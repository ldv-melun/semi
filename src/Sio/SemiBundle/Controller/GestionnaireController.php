<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sio\SemiBundle\Controller\DefaultController;
use Symfony\Component\HttpFoundation\Request;
use Sio\SemiBundle\Entity\Participant;

/**
 * @Route("/gestion")
 */
class GestionnaireController extends Controller
{
    /**
     * @Route("/export")
     * @Template()
     */
    public function exportAction()
    {
    	return array();
    }
    
     /**
     * @Route("/ajout-participant", name="ajout_participant")
     * @Template()
     */
    public function ajoutParticipantAction(Request $request)
    {
            $participant = new Participant();
            $form = $this->createFormBuilder($participant)
               ->add('mail', 'text')
               ->getForm();

               $request = $this->get('request');
               // On vérifie qu'elle est de type POST
               if ($request->getMethod() == 'POST') {

                   $form->bind($request);

                   if ($form->isValid()) {
                       $mdp = DefaultController::genereMdp();
                       
                       $em = $this->getDoctrine()->getManager();
                       
                       $participant->setMail($form->get('mail')->getData());
                       $participant->setPassword($mdp);
                       $participant->setSalt("");
                       $participant->setRoles(array('ROLE_PARTICIPANT'));
                       $participant->setDateCrea(new \DateTime());
                       
                       $em->persist($participant);
                       $em->flush();
                       
                       $info = array();
                       $info['mail'] = $form->get('mail')->getData();
                       $info['mdp'] = $mdp;
                       $info['cle'] = $this->get('session')->get('cle');
                       return array('form'=>$form->createView(),
                                                'informations'=> $info);
                   }
               }
             return array('form'=>$form->createView());
    }

}
