<?php

namespace App\EntityListner;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, entity: Reservation::class)]
class ReservationEntityListner
{
    public function __construct(private Security $security) {}

    public function prePersist(Reservation $reservation)
    {
        $reservation->setUser($this->security->getUser());
    }
}
