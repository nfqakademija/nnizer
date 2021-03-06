<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReviewRepository")
 */
class Review
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contractor", inversedBy="reviews", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $contractor;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"frontPage", "filtered"})
     */
    private $stars;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Reservation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"frontPage"})
     */
    private $reservation;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"frontPage"})
     */
    private $description;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Contractor|null
     */
    public function getContractor(): ?Contractor
    {
        return $this->contractor;
    }

    /**
     * @param Contractor $contractor
     * @return $this
     */
    public function setContractor(Contractor $contractor): self
    {
        $this->contractor = $contractor;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStars(): ?int
    {
        return $this->stars;
    }

    /**
     * @param int $stars
     * @return $this
     */
    public function setStars(int $stars): self
    {
        $this->stars = $stars;

        return $this;
    }

    /**
     * @return Reservation|null
     */
    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    /**
     * @param Reservation $reservation
     * @return $this
     */
    public function setReservation(Reservation $reservation): self
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
