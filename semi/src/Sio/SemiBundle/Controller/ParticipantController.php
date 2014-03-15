<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/participant")
 */
class ParticipantController extends Controller
{
	/**
	 * @Route("/index", name="participant")
	 * @Template()
	 */
	public function indexAction()
	{
		$lesParticipant = $this->getDoctrine()
		->getRepository("SioSemiBundle:Participant")
		->findAll();
		return array('lesParticipant' => $lesParticipant);
	}
	
}
