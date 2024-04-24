<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\Timer;
use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[ApiResource(
    operations: [
        /**
         * /api/cars/{id}
         */
        new Get(normalizationContext: ['groups' => 'car:item']),
        /**
         * /api/cars
         */
        new GetCollection(normalizationContext: ['groups' => 'car:list'])
    ],
    order: ['createdAt' => 'DESC'],
)]
class Car
{
    use Timer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['car:list', 'car:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['car:list', 'car:item'])]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    #[Groups(['car:list', 'car:item', 'reservation:read'])]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    #[Groups(['car:list', 'car:item', 'reservation:read'])]
    private ?string $model = null;

    #[ORM\Column(length: 255)]
    #[Groups(['car:list', 'car:item'])]
    private ?string $color = null;

    #[ORM\Column]
    #[Groups(['car:list', 'car:item', 'reservation:read'])]
    private ?float $priceLocation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['car:item'])]
    private ?string $content = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'car')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getPriceLocation(): ?float
    {
        return $this->priceLocation;
    }

    public function setPriceLocation(float $priceLocation): static
    {
        $this->priceLocation = $priceLocation;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setCar($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getCar() === $this) {
                $reservation->setCar(null);
            }
        }

        return $this;
    }
}
