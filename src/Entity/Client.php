<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Client
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fk_contractor;

    /**
     * @ORM\Column(type="datetime")
     */
    private $visit_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_verified;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $verification_key;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cancel_key;

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
     * @return string|null
     */
    public function getFkContractor(): ?string
    {
        return $this->fk_contractor;
    }

    /**
     * @param string $fk_contractor
     * @return $this
     */
    public function setFkContractor(string $fk_contractor): self
    {
        $this->fk_contractor = $fk_contractor;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getVisitDate(): ?\DateTimeInterface
    {
        return $this->visit_date;
    }

    /**
     * @param \DateTimeInterface $visit_date
     * @return $this
     */
    public function setVisitDate(\DateTimeInterface $visit_date): self
    {
        $this->visit_date = $visit_date;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    /**
     * @param bool $is_verified
     * @return $this
     */
    public function setIsVerified(bool $is_verified): self
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVerificationKey(): ?string
    {
        return $this->verification_key;
    }

    /**
     * @param string|null $verification_key
     * @return $this
     */
    public function setVerificationKey(?string $verification_key): self
    {
        $this->verification_key = $verification_key;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCancelKey(): ?string
    {
        return $this->cancel_key;
    }

    /**
     * @param string|null $cancel_key
     * @return $this
     */
    public function setCancelKey(?string $cancel_key): self
    {
        $this->cancel_key = $cancel_key;

        return $this;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateActivationKey(): string
    {
        return sha1(random_bytes(10));
    }
}
