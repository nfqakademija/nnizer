<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServiceTypeRepository")
 */
class ServiceType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"nameOnly"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Contractor", inversedBy="services", cascade={"persist"})
     */
    private $contractors;

    public function __construct()
    {
        $this->contractors = new ArrayCollection();
    }

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Contractor[]
     */
    public function getContractors(): Collection
    {
        return $this->contractors;
    }

    /**
     * @param Contractor $contractor
     * @return $this
     */
    public function addContractor(Contractor $contractor): self
    {
        if (!$this->contractors->contains($contractor)) {
            $this->contractors[] = $contractor;
        }

        return $this;
    }

    /**
     * @param Contractor $contractor
     * @return $this
     */
    public function removeContractor(Contractor $contractor): self
    {
        if ($this->contractors->contains($contractor)) {
            $this->contractors->removeElement($contractor);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
