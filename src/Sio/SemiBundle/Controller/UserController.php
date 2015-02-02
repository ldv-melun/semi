<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sio\SemiBundle\Entity\Seminar as Seminar;

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
            $user = $this->getUser();
            
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT userseminar
                FROM SioSemiBundle:UserSeminar userseminar
                WHERE userseminar.user = '.$user->getId().'
                ORDER BY userseminar.id'
            );

            $userSeminar = $query->getResult();
            foreach($userSeminar as $seminar)
            {
                $seminars[] = $this->getDoctrine()->getRepository("SioSemiBundle:Seminar")->findBy(array("id" => $seminar->getSeminar()->getId()));
            }
            
            foreach($seminars as $tab)
            {
                foreach($tab as $seminar)
                {
                    $meeting[] = $this->getDoctrine()->getRepository("SioSemiBundle:Meeting")->findBy(array("seminar" => $seminar->getId()));
                }
            }
            
            return array("seminars" => $seminars, "meetings" => $meeting);
	}
	
}
