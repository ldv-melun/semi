<?php

namespace Sio\UserBundle\EventListener;

/**
 * Description of RegistrationListener
 *
 * @author kpu
 */ 
 
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
Use Sio\SemiBundle\SioSemiConstants;

/**
 * EventListener permettant d'écouteur les évènements déclenchés par FOSUserBundle
 * au moment de l'enregistrement d'un utilisateur.
 */
class RegistrationListener implements EventSubscriberInterface
{
   private $helper;
   
   public function __construct(\Sio\UserBundle\Service\HelperRegistration $helper) {
     $this->helper = $helper;
   }


   /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationComplete',
            FOSUserEvents::REGISTRATION_INITIALIZE => 'onRegistrationInit'
        );
    }
 
    /**
     * 
     * @param \FOS\UserBundle\Event\UserEvent $userEvent
     */
    public function onRegistrationComplete(UserEvent $userEvent)
    {
      $idStatus = null; // so let helper get it in request form
      $this->helper->registerUserSeminar($idStatus);    
    }
    
    
    /**
     * Push email in session into registration form (via user objet)
     * @param \FOS\UserBundle\Event\UserEvent $userEvent
     */
    public function onRegistrationInit(UserEvent $userEvent)
    {
      $user = $userEvent->getUser();
      $session = $userEvent->getRequest()->getSession();
      if ($session->has(SioSemiConstants::EMAIL_FOR_REGISTER)) :
       $user->setEmail($session->get(SioSemiConstants::EMAIL_FOR_REGISTER));
      endif;
    }
    
}