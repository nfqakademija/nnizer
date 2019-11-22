<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Contractor", inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contractor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $authorFirstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $authorLastname;

    /**
     * @ORM\Column(type="integer")
     */
    private $stars;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

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
     * @param Contractor|null $contractor
     * @return $this
     */
    public function setContractor(?Contractor $contractor): self
    {
        $this->contractor = $contractor;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthorFirstname(): ?string
    {
        return $this->authorFirstname;
    }

    /**
     * @param string $authorFirstname
     * @return $this
     */
    public function setAuthorFirstname(string $authorFirstname): self
    {
        $this->authorFirstname = $authorFirstname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthorLastname(): ?string
    {
        return $this->authorLastname;
    }

    /**
     * @param string $authorLastname
     * @return $this
     */
    public function setAuthorLastname(string $authorLastname): self
    {
        $this->authorLastname = $authorLastname;

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
