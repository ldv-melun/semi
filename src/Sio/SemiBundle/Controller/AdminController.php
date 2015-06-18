<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sio\SemiBundle\Entity\Parameter as Parameter;


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
    	return array();
    }
    
    /**
     * @Route("/parameter", name="_semi_admin_parameter")
     * @Template()
     */
    public function parameterAction()
    {
      $getParameters = $this->getDoctrine()->getRepository("SioSemiBundle:Parameter")->findAll();
      $nativeParameters = array();
      $p = $getParameters[0];
    	return array('clef'=>$p->getClef(), 'valeur' => $p->getValue());
    }

    /**
     * 
     * @Route("/parameter/check", name="_semi_admin_parameter_check")
     * @Template()
     */
    public function parameterCheckAction()
    {
      $request = Request::createFromGlobals();
      // Update the native key.
      $param = $this->getDoctrine()
          ->getRepository("SioSemiBundle:Parameter")
          ->findOneBy(array('clef' => $request->get('clef1')));
      $oldValue = $param->getValue();
      if ($oldValue != $request->get('value1')) {
        $param->setValue($request->get('value1'));
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($param);
        $manager->flush();
        
        $this->get('session')->getFlashBag()->add('success', 
          'Clé '.$request->get('clef1').' mise à jour : ' 
          . $oldValue . ' => ' . $request->get('value1'));
      }else {
        $this->get('session')->getFlashBag()->add('warning', 
          'Rien à mettre à jour');
      }
      return $this->redirect($this->generateUrl('_semi_admin_parameter'));
    }
}
