<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Response;

class JwtTokenTest extends ApiTestCase
{
    private $client = null;

    /**
     * Init client test
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * Test endpoint login with valid data
     */
    public function testLoginWithValidData(): void
    {
        $response = $this->login("salah@gmail.com", "test");

        $content = $response->getContent();

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJson($content);

        $this->assertArrayHasKey('token', json_decode($content, true));
    }

    /**
     * Test endpoint login with valid data
     */
    public function testLoginWithInValidData(): void
    {
        $response = $this->login("salah21@gmail.com", "test123");

        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * Function login
     */
    private function login($email, $password): Response
    {
        $url = "http://localhost:8000/api/login";

        $response = $this->client->request('POST', $url, [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'json' => [
                'email' => $email,
                'password' => $password
            ]
        ]);

        return $response;
    }
}
