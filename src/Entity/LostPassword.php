<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LostPasswordRepository")
 */
class LostPassword
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Contractor", inversedBy="lostPassword", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $contractor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $resetKey;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;

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
     * @return string|null
     */
    public function getResetKey(): ?string
    {
        return $this->resetKey;
    }

    /**
     * @param string $resetKey
     * @return $this
     */
    public function setResetKey(string $resetKey): self
    {
        $this->resetKey = $resetKey;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTimeInterface $expiresAt
     * @return $this
     */
    public function setExpiresAt(\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }
}
