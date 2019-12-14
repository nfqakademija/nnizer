<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\ContractorRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"username"}, message="username.existing")
 * @ORM\Table(
 *      indexes={
 *          @ORM\Index(name="idx_key", columns={"verification_key"})
 *      }
 * )
 */
class Contractor implements UserInterface
{
    use TimestampableTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"frontPage", "filtered"})
     */
    private $username;
    
    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string
     */
    private $plainPassword = null;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=32)
     * @Groups({"frontPage", "filtered"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=32)
     * @Groups({"frontPage", "filtered"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"frontPage"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     * @Groups({"frontPage"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $verificationKey;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ContractorSettings", mappedBy="contractor", cascade={"persist", "remove"})
     */
    private $settings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="contractor", orphanRemoval=true)
     * @Groups({"frontPage", "filtered"})
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="contractor", orphanRemoval=true)
     */
    private $reservations;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Groups({"frontPage", "filtered"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Groups({"frontPage", "filtered"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Groups({"frontPage"})
     */
    private $facebook;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"frontPage"})
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ProfilePhoto", mappedBy="Contractor", cascade={"persist", "remove"})
     * @Groups({"frontPage"})
     */
    private $profilePhoto;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CoverPhoto", mappedBy="Contractor", cascade={"persist", "remove"})
     * @Groups({"frontPage"})
     */
    private $coverPhoto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ServiceType", inversedBy="contractors", cascade={"persist"})
     * @Groups({"frontPage"})
     */
    private $services;

    /**
     * Contractor constructor.
     */
    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_CONTRACTOR';

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $plainPassword
     * @return $this
     */
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
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
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|null $phoneNumber
     * @return $this
     */
    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

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
     * @ORM\PrePersist()
     * @return $this
     * @throws \Exception
     */
    public function setVerificationKey(): self
    {
        $this->verificationKey = $this->generateVerificationKey();

        return $this;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateVerificationKey()
    {
        return sha1(random_bytes(10));
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
     * @return ContractorSettings|null
     */
    public function getSettings(): ?ContractorSettings
    {
        return $this->settings;
    }

    /**
     * @param ContractorSettings $settings
     * @return $this
     */
    public function setSettings(ContractorSettings $settings): self
    {
        $this->settings = $settings;

        // set the owning side of the relation if necessary
        if ($settings->getContractor() !== $this) {
            $settings->setContractor($this);
        }

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    /**
     * @param Review $review
     * @return $this
     */
    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setContractor($this);
        }

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    /**
     * @param Reservation $reservation
     * @return $this
     */
    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setContractor($this);
        }

        return $this;
    }

    /**
     * @param Reservation $reservation
     * @return $this
     */
    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getContractor() === $this) {
                $reservation->setContractor(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    /**
     * @param string|null $facebook
     * @return $this
     */
    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

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

    /**
     * @return ProfilePhoto|null
     */
    public function getProfilePhoto(): ?ProfilePhoto
    {
        return $this->profilePhoto;
    }

    /**
     * @param ProfilePhoto $profilePhoto
     * @return $this
     */
    public function setProfilePhoto(ProfilePhoto $profilePhoto): self
    {
        $this->profilePhoto = $profilePhoto;

        // set the owning side of the relation if necessary
        if ($profilePhoto->getContractor() !== $this) {
            $profilePhoto->setContractor($this);
        }

        return $this;
    }

    /**
     * @return CoverPhoto|null
     */
    public function getCoverPhoto(): ?CoverPhoto
    {
        return $this->coverPhoto;
    }

    /**
     * @param CoverPhoto $coverPhoto
     * @return $this
     */
    public function setCoverPhoto(CoverPhoto $coverPhoto): self
    {
        $this->coverPhoto = $coverPhoto;

        // set the owning side of the relation if necessary
        if ($coverPhoto->getContractor() !== $this) {
            $coverPhoto->setContractor($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function setDefaultProfilePhoto(): self
    {
        $profilePhoto = new ProfilePhoto();
        $profilePhoto->setFilename('default.png');
        $this->setProfilePhoto($profilePhoto);

        return $this;
    }

    /**
     * @return ServiceType|null
     */
    public function getServices(): ?ServiceType
    {
        return $this->services;
    }

    /**
     * @param ServiceType $service
     * @return $this
     */
    public function setServices(ServiceType $service): self
    {
        $this->services = $service;

        $service->addContractor($this);

        return $this;
    }
}
