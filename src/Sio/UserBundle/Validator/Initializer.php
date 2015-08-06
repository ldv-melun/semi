<?php


namespace Sio\UserBundle\Validator;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Validator\Initializer as FUBInitializer;

/**
 * Override a service (see FUB/Resources/config/validaor.xml 
 * Automatically updates the canonical fields before validation.
 * Put email value into username
 * 
 * @author kpu
 */
class Initializer extends FUBInitializer
{
    
    public function __construct(UserManagerInterface $userManager)
    {
        parent::__construct($userManager);
    }

    public function initialize($object)
    {
      parent::initialize($object);
      if ($object instanceof UserInterface) {
        $user = $object;
        $user->setUsername($user->getEmailCanonical());
        $user->setUsernameCanonical($user->getEmailCanonical());        
      }
    }
    
}
