<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testCreateUser()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'testpass123'
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
    }
}
