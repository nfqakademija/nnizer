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
     * @ORM\Column(type="dateinterval")
     */
    private $Monday;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private $Tuesday;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private $Wednesday;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private $Thursday;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private $Friday;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private $Saturday;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private $Sunday;

    /**
     * @ORM\Column(type="time")
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
     * @return \DateInterval|null
     */
    public function getMonday(): ?\DateInterval
    {
        return $this->Monday;
    }

    /**
     * @param \DateInterval $Monday
     * @return $this
     */
    public function setMonday(\DateInterval $Monday): self
    {
        $this->Monday = $Monday;

        return $this;
    }

    /**
     * @return \DateInterval|null
     */
    public function getTuesday(): ?\DateInterval
    {
        return $this->Tuesday;
    }

    /**
     * @param \DateInterval $Tuesday
     * @return $this
     */
    public function setTuesday(\DateInterval $Tuesday): self
    {
        $this->Tuesday = $Tuesday;

        return $this;
    }

    /**
     * @return \DateInterval|null
     */
    public function getWednesday(): ?\DateInterval
    {
        return $this->Wednesday;
    }

    /**
     * @param \DateInterval $Wednesday
     * @return $this
     */
    public function setWednesday(\DateInterval $Wednesday): self
    {
        $this->Wednesday = $Wednesday;

        return $this;
    }

    /**
     * @return \DateInterval|null
     */
    public function getThursday(): ?\DateInterval
    {
        return $this->Thursday;
    }

    /**
     * @param \DateInterval $Thursday
     * @return $this
     */
    public function setThursday(\DateInterval $Thursday): self
    {
        $this->Thursday = $Thursday;

        return $this;
    }

    /**
     * @return \DateInterval|null
     */
    public function getFriday(): ?\DateInterval
    {
        return $this->Friday;
    }

    /**
     * @param \DateInterval $Friday
     * @return $this
     */
    public function setFriday(\DateInterval $Friday): self
    {
        $this->Friday = $Friday;

        return $this;
    }

    /**
     * @return \DateInterval|null
     */
    public function getSaturday(): ?\DateInterval
    {
        return $this->Saturday;
    }

    /**
     * @param \DateInterval $Saturday
     * @return $this
     */
    public function setSaturday(\DateInterval $Saturday): self
    {
        $this->Saturday = $Saturday;

        return $this;
    }

    /**
     * @return \DateInterval|null
     */
    public function getSunday(): ?\DateInterval
    {
        return $this->Sunday;
    }

    /**
     * @param \DateInterval $Sunday
     * @return $this
     */
    public function setSunday(\DateInterval $Sunday): self
    {
        $this->Sunday = $Sunday;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getVisitDuration(): ?\DateTimeInterface
    {
        return $this->visitDuration;
    }

    /**
     * @param \DateTimeInterface $visitDuration
     * @return $this
     */
    public function setVisitDuration(\DateTimeInterface $visitDuration): self
    {
        $this->visitDuration = $visitDuration;

        return $this;
    }
}
