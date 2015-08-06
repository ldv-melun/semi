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
    	return array('menuItemActive' => 'admin');
    }
    
    /**
     * @Route("/users", name="_semi_admin_users")
     * @Template()
     */
    public function usersAction()
    {
    	return array('menuItemActive' => 'admin');
    }
    
    /**
     * @Route("/seminars", name="_semi_admin_seminars")
     * @Template()
     */
    public function seminarsAction()
    {
    	return array('menuItemActive' => 'admin');
    }
    
}
