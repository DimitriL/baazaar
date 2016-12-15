<?php

namespace Baazaar\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/user/register');
        $response = $client->getResponse();
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Register', $response->getContent());
    }
}
