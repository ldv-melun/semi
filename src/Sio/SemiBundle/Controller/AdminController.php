<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sio\SemiBundle\Entity\Parameter as Parameter;

/*
 * [FR ONLY]
 * TODO : Protéger les clés contre l'override.
 * 
 * Note : Les clefs natives sont FIXES. C'est pour ça qu'elles ne possèdent
 * pas d'attribut "rememberX". A l'inverse, les chefs optionnelles peuvent
 * être modifiées. On a donc besoin de se souvenir de l'ancienne clé :
 * "rememberOptX" ou X = init (Jquery), i (Twig/Symfony).
 */

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
        $getNativeParameters = array("organisation"); // Used by the native system. They have to be declared.
        $nbNative = count($getNativeParameters);
        
        $nativeParameters = array();
        $parameters = array();
        
        foreach($getParameters as $object)
        {
            for($i = 0; $i < $nbNative; $i++)
            {
                if($object->getClef() == $getNativeParameters[$i])
                {
                    // Is a native key.
                    $nativeParameters[] = array($object->getClef() => $object->getValue());
                }
                else
                {
                    // Is NOT a native key.
                    $parameters[] = array($object->getClef() => $object->getValue());
                }
            }
        }
        
    	return array('parameters' => $parameters, 'nativeParameters' => $nativeParameters);
    }

    /**
     * @Route("/parameter/check", name="_semi_admin_parameter_check")
     * @Template()
     */
    public function parameterCheckAction()
    {
        $request = Request::createFromGlobals();
        $i = 1; // "init" Jquery || "i" Twig
        $bypass = false;
        
        while(!$bypass)
        {
            // Native key.
            if(null !== $request->get('clef'.$i))
            {
                // Got a non-null, native ID key.
                if(null == $request->get('value'.$i))
                {
                    // Native key value can't be null.
                    $this->get('session')->getFlashBag()->add('warning', 'La clé '.$request->get('clef'.$i).' n\'a pas été sauvegardée : sa valeur n\'a pas été indiquée !');
                }
                else
                {
                    // Update the native key.
                    $param = $this->getDoctrine()->getRepository("SioSemiBundle:Parameter")->findOneBy(array('clef' => $request->get('clef'.$i)));
                    $param->setValue($request->get('value'.$i));
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($param);
                    $manager->flush();
                }
            }
            // Optional key.
            elseif(null !== $request->get('clefOpt'.$i) && $request->get('clefOpt'.$i) != "")
            {
                // Got a non-null, optional ID key.
                if(null == $request->get('valueOpt'.$i))
                {
                    // Optional key value can't be null.
                    $this->get('session')->getFlashBag()->add('warning', 'La clé '.$request->get('clefOpt'.$i).' n\'a pas été sauvegardée : sa valeur n\'a pas été indiquée !');
                }
                else
                {
                    $getOptParam = $this->getDoctrine()->getRepository("SioSemiBundle:Parameter")->findOneBy(array('clef' => $request->get('rememberOpt'.$i)));
                    if($getOptParam)
                    {
                        // Update state.
                        $getOptParam->setClef($request->get('clefOpt'.$i));
                        $getOptParam->setValue($request->get('valueOpt'.$i));
                        $manager = $this->getDoctrine()->getManager();
                        $manager->persist($getOptParam);
                        $manager->flush();
                    }
                    else
                    {
                        // Insert state.
                        $newParam = new Parameter();
                        $newParam->setClef($request->get('clefOpt'.$i));
                        $newParam->setValue($request->get('valueOpt'.$i));
                        $manager = $this->getDoctrine()->getManager();
                        $manager->persist($newParam);
                        $manager->flush();
                    }
                }
            }
            else
            {
                $bypass = true;
                $this->get('session')->getFlashBag()->add('success', 'Changements effectués !');
            }
            
            $i++;
        }

        // Redirect to the main page.
        return $this->redirect($this->generateUrl('_semi_admin_parameter'));
    }
}
