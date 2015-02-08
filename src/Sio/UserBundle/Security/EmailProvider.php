<?php

/*
 * Special provider for use by FOSUser
 * 
 * Load user by email only
 * 
 * see : app/config/config.yml  (service declaration)
 * see : app/config/security.yml (providers declaration)
 * see : UserBundle/Ressources/config/services.xml
 * from kpu idea (to test !!)
 */

namespace Sio\UserBundle\Security;

use FOS\UserBundle\Security\UserProvider;

class EmailProvider extends UserProvider
{
    /**
     * {@inheritDoc}
     */
    protected function findUser($username)
    {
        return $this->userManager->findUserByEmail($username);
    }
}
