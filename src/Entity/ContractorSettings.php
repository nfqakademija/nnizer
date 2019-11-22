<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContractorSettingsRepository")
 */
class ContractorSettings
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Contractor", inversedBy="settings", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $contractor;

    /**
     * @ORM\Column(type="string")
     */
    private $Monday;

    /**
     * @ORM\Column(type="string")
     */
    private $Tuesday;

    /**
     * @ORM\Column(type="string")
     */
    private $Wednesday;

    /**
     * @ORM\Column(type="string")
     */
    private $Thursday;

    /**
     * @ORM\Column(type="string")
     */
    private $Friday;

    /**
     * @ORM\Column(type="string")
     */
    private $Saturday;

    /**
     * @ORM\Column(type="string")
     */
    private $Sunday;

    /**
     * @ORM\Column(type="integer")
     */
    private $visitDuration;

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
     * @return string|null
     */
    public function getMonday(): ?string
    {
        return $this->Monday;
    }

    /**
     * @param string $Monday
     * @return $this
     */
    public function setMonday(string $Monday): self
    {
        $this->Monday = $Monday;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTuesday(): ?string
    {
        return $this->Tuesday;
    }

    /**
     * @param string $Tuesday
     * @return $this
     */
    public function setTuesday(string $Tuesday): self
    {
        $this->Tuesday = $Tuesday;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWednesday(): ?string
    {
        return $this->Wednesday;
    }

    /**
     * @param string $Wednesday
     * @return $this
     */
    public function setWednesday(string $Wednesday): self
    {
        $this->Wednesday = $Wednesday;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getThursday(): ?string
    {
        return $this->Thursday;
    }

    /**
     * @param string $Thursday
     * @return $this
     */
    public function setThursday(string $Thursday): self
    {
        $this->Thursday = $Thursday;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFriday(): ?string
    {
        return $this->Friday;
    }

    /**
     * @param string $Friday
     * @return $this
     */
    public function setFriday(string $Friday): self
    {
        $this->Friday = $Friday;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSaturday(): ?string
    {
        return $this->Saturday;
    }

    /**
     * @param string $Saturday
     * @return $this
     */
    public function setSaturday(string $Saturday): self
    {
        $this->Saturday = $Saturday;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSunday(): ?string
    {
        return $this->Sunday;
    }

    /**
     * @param string $Sunday
     * @return $this
     */
    public function setSunday(string $Sunday): self
    {
        $this->Sunday = $Sunday;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getVisitDuration(): ?int
    {
        return $this->visitDuration;
    }

    /**
     * @param int $visitDuration
     * @return $this
     */
    public function setVisitDuration(int $visitDuration): self
    {
        $this->visitDuration = $visitDuration;

        return $this;
    }
}
