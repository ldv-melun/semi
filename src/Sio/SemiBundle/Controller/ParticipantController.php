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
	 * @Route("/index")
	 * @Template()
	 */
	public function indexAction()
	{
		return array();
	}
	
}
