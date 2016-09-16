<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
Use Symfony\Component\HttpFoundation\JsonResponse;
Use Sio\SemiBundle\SioSemiConstants;

class DefaultController extends Controller
{

  /**
     * @Route("/", name="_semi_default_index")
     */
    public function indexAction()
    {
      $user = $this->getUser();
      if (! $user) {
        return $this->redirect($this->generateUrl(SioSemiConstants::ROUTE_LOGIN));
      } else {
        return $this->redirect($this->generateUrl('_semi_seminar_index'));
      }
    }

    /**
     * @return JSon
     *  {clefOk: true|false, userKnown: true|false, emailSyntaxOk: true|false}
     * @Route("/checkclef-mail", name="_semi_default_check_clef_mail")
     */
    public function checkClefAndEmailAction(Request $request)
    {
       $seminarClef = $request->request->get('seminar-clef', null);
       $emailUser = $request->request->get('email-user', null);


       if ($seminarClef) {
          $seminar = $this->getDoctrine()
               ->getRepository('SioSemiBundle:Seminar')
               ->findOneBy(array('clef' => $seminarClef));
       } else {
          $seminar = null;
       }
       $emailSyntax = filter_var($emailUser, FILTER_VALIDATE_EMAIL);

       if ($emailUser && $emailSyntax) {
          $semiUser = $this->getDoctrine()
              ->getRepository('SioUserBundle:User')
              ->findOneBy(array('email' => $emailUser));
       } else {
          $semiUser = null;
       }

       // put clef into session
       if ($seminar) {
           $request->getSession()->set(SioSemiConstants::SEMINAR_KEY, $seminarClef);
           $request->getSession()->set(SioSemiConstants::SEMINAR_ID, $seminar->getId());
       }
       // put email into session for register
       if ($emailSyntax) {
           $request->getSession()->set(SioSemiConstants::EMAIL_FOR_REGISTER, $emailUser);
       }

       $data = array('clefOk'=>$seminar != false,
                     'userKnown' => $semiUser != false,
                     'emailSyntaxOk'=> $emailSyntax == true);

       return new JsonResponse($data);
    }


    /**
     * @Route("/redirect", name="_semi_default_redirect")
     * @Template()
     */
    public function redirectAction(Request $request)
    {
        $session = $request->getSession();
        $idSeminar = $session->get(SioSemiConstants::SEMINAR_ID);
        // Redirect the user, will also update ipLastLogin & dateLastLogin.
        $response = new Response();
        $response->setStatusCode(200);

        if($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            // ipLastLogin & dateLastLogin
            $user = $this->getUser();
            $user->setIpLastLogin($this->container->get('request')->getClientIp());
            $user->setDateLastLogin(new \DateTime('now'));
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            if(true === $this->get('security.context')->isGranted('ROLE_ADMIN'))
            {
              return $this->redirect($this->generateUrl('_semi_seminar_index') . "$idSeminar");
            }
            elseif(true === $this->get('security.context')->isGranted('ROLE_MANAGER'))
            {
                $response->headers->set('Refresh', '5; url='.$this->generateUrl('_semi_manager_index'));
                $response->send();

                return array('firstName' => $user->getFirstName(), 'role' => $this->generateUrl('_semi_manager_index'));
            }
            elseif(true === $this->get('security.context')->isGranted('ROLE_USER'))
            {
                $response->headers->set('Refresh', '5; url='.$this->generateUrl('_semi_seminar_index') . "$idSeminar");
                $response->send();

                return array('firstName' => $user->getFirstName(), 'role' => $this->generateUrl('_semi_seminar_index'));
            }
        }
        // Access trough ROLE_ANONYMOUS.
        return $this->redirect($this->generateUrl('_semi_default_index'));
    }
}
