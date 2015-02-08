<?php

namespace Sio\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SioUserBundle extends Bundle
{
   public function getParent()
    {
        return 'FOSUserBundle';
    }
}
