<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Response;

class ReservationTest extends ApiTestCase
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

    private function createReservationData($data): Response
    {
        /** get current user by token */
        $token = $this->getUserToken();

        $url = self::BASE_URI . "/api/reservations";

        $response = $this->client->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json', 
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ],
            'extra' => [
                'parameters' => $data
            ]
        ]);

        return $response;
    }

    /**
     * Test endpoint user to create a new reservation with valid data
     */
    public function testCreateReservationValidData()
    {
        $data = [
            'startDate' => '2024-04-24 00:00:00',
            'endDate' => '2024-05-01 00:00:00',
        ];

        $response = $this->createReservationData($data);

        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());
    }

    /**
     * Test endpoint user to create a new reservation with invalid data
     */
    public function testCreateReservationInValidData()
    {
        $data = [
            'startDate' => new \DateTime('2024-04-24 00:00:00'),
            'endDate' => new \DateTime('2023-05-01 00:00:00'),
        ];

        $response = $this->createReservationData($data);

        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * Test endpoint list reservations by user
     */
    public function testListReservationByUser()
    {
        /** get current user by token */
        $token = $this->getUserToken();

        $userId = static::$kernel->getContainer()->get('security.token_storage')->getUser()->getId();

        $url = self::BASE_URI . "/api/users/$userId/reservations";

        $response = $this->client->request('GET', $url, [
            'headers' => [
                'Content-Type' => 'application/json', 
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());
    }

    /**
     * Test endpoint edit reservation by user
     */
    public function tesEditReservationByUser()
    {
        /** get current user by token */
        $token = $this->getUserToken();

        $user = static::$kernel->getContainer()->get('security.token_storage')->getUser();

        $reservation = $user->getReservations()->first();

        $url = self::BASE_URI . "/api/reservations/" . $reservation->getId();

        $response = $this->client->request('PUT', $url, [
            'headers' => [
                'Content-Type' => 'application/json', 
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ],
            'extra' => [
                'parameters' => [
                    'startDate' => '2024-04-24 00:00:00',
                    'endDate' => '2024-05-02 00:00:00',
                ]
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());
    }

    /**
     * Test endpoint delete reservation by user
     */
    public function tesDeleteReservationByUser()
    {
        /** get current user by token */
        $token = $this->getUserToken();

        $user = static::$kernel->getContainer()->get('security.token_storage')->getUser();

        $reservation = $user->getReservations()->first();

        $url = self::BASE_URI . "/api/reservations/" . $reservation->getId();

        $response = $this->client->request('DELETE', $url, [
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
