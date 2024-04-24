<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_USER")]
class ReservationController extends AbstractController
{
    #[Route('/api/users/{id}/reservations', name: 'user_reservations', methods: ['GET'])]
    public function findReservationsByUser(User $user): Response
    {
        if ($user == null) {
            return $this->json(['message' => 'Not found'], 404);
        }

        if ($user != $this->getUser()) {
            return $this->json(['message' => 'Not authorized'], 401);
        }

        // Return a success response
        return $this->json(['data' => $user->getReservations()], Response::HTTP_CREATED, context: ['groups' => 'reservation:read']);
    }
}
