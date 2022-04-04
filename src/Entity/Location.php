<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Reservation::class)]
    private $reservations;

    #[ORM\Column(type: 'array', nullable: true)]
    private $daysOfTheWeekNotAvailable = [];

    #[ORM\Column(type: 'array', nullable: true)]
    private $datesNotAvailable = [];

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setLocation($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getLocation() === $this) {
                $reservation->setLocation(null);
            }
        }

        return $this;
    }

    public function getDaysOfTheWeekNotAvailable(): ?array
    {
        return $this->daysOfTheWeekNotAvailable;
    }

    public function setDaysOfTheWeekNotAvailable(?array $daysOfTheWeekNotAvailable): self
    {
        $this->daysOfTheWeekNotAvailable = $daysOfTheWeekNotAvailable;

        return $this;
    }

    public function getDatesNotAvailable(): ?array
    {
        return $this->datesNotAvailable;
    }

    public function setDatesNotAvailable(?array $datesNotAvailable): self
    {
        $this->datesNotAvailable = $datesNotAvailable;

        return $this;
    }
}
