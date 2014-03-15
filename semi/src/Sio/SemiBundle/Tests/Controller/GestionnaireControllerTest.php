<?php

namespace Sio\SemiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GestionnaireControllerTest extends WebTestCase
{
    public function testExport()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/export');
    }

}
