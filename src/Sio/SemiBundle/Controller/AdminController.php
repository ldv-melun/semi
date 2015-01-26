<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="_semi_admin_index")
     * @Template()
     */
    public function indexAction()
    {
        /* EXAMPLE
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
         * 
         */
    	return array();
    }

}
