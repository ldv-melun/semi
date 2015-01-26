<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
	/**
	 * @Route("/", name="_semi_user_index")
	 * @Template()
	 */
	public function indexAction()
	{
		return array();
	}
	
}
