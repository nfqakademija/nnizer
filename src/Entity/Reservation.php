<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Reservation
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"Default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Default", "frontPage"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Default", "frontPage"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Default"})
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"Default", "frontPage"})
     */
    private $visitDate;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"Default"})
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $verificationKey;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $verificationKeyExpirationDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"Default"})
     */
    private $isCancelled = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCompleted = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contractor", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contractor;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return $this
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return $this
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getVisitDate(): ?\DateTimeInterface
    {
        return $this->visitDate;
    }

    /**
     * @param \DateTimeInterface $visitDate
     * @return $this
     */
    public function setVisitDate(\DateTimeInterface $visitDate): self
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    /**
     * @param bool $isVerified
     * @return $this
     */
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVerificationKey(): ?string
    {
        return $this->verificationKey;
    }

    /**
     * @param string|null $verificationKey
     * @return $this
     */
    public function setVerificationKey(?string $verificationKey): self
    {
        $this->verificationKey = $verificationKey;

        return $this;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function generateActivationKey(): string
    {
        return sha1(random_bytes(10));
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getVerificationKeyExpirationDate(): ?\DateTimeInterface
    {
        return $this->verificationKeyExpirationDate;
    }

    /**
     * @param \DateTimeInterface|null $verificationKeyExpirationDate
     * @return $this
     */
    public function setVerificationKeyExpirationDate(?\DateTimeInterface $verificationKeyExpirationDate): self
    {
        $this->verificationKeyExpirationDate = $verificationKeyExpirationDate;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsCancelled(): ?bool
    {
        return $this->isCancelled;
    }

    /**
     * @param bool|null $isCancelled
     * @return $this
     */
    public function setIsCancelled(?bool $isCancelled): self
    {
        $this->isCancelled = $isCancelled;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsCompleted(): bool
    {
        return $this->isCompleted;
    }

    /**
     * @param bool $isCompleted
     * @return $this
     */
    public function setIsCompleted(bool $isCompleted): self
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    /**
     * @return Contractor|null
     */
    public function getContractor(): ?Contractor
    {
        return $this->contractor;
    }

    /**
     * @param Contractor|null $Contractor
     * @return $this
     */
    public function setContractor(?Contractor $Contractor): self
    {
        $this->contractor = $Contractor;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->visitDate->format('Y-m-d H:i:s');
    }
}
