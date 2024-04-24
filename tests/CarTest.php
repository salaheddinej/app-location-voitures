<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Response;

class CarTest extends ApiTestCase
{
    const BASE_URI = 'http://localhost:8000';

    private $client = null;

    /**
     * Init client test
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * Test endpoint list available cars
     */
    public function testListAvailableCars()
    {
        /** get current user by token */
        $token = $this->getUserToken();

        $url = self::BASE_URI . "/api/cars";

        $response = $this->client->request('GET', $url, [
            'headers' => [
                'Content-Type' => 'application/json', 
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());
    }


    /**
     * Get token login user
     */
    private function getUserToken(): string
    {
        $url = self::BASE_URI . "/api/login";

        $response = $this->client->request('POST', $url, [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'json' => [
                'email' => 'salah@gmail.com',
                'password' => 'test'
            ]
        ]);

        $data = json_decode($response->getContent(), true);

        return $data['token'];
    }
}
