<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfilePhotoRepository")
 * @Vich\Uploadable()
 */
class ProfilePhoto
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"frontPage"})
     */
    private $filename;

    /**
     * @Vich\UploadableField(mapping="contractorsProfile", fileNameProperty="profilePhotoFilename")
     *
     * @var File
     */
    private $profilePhoto;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Contractor", inversedBy="profilePhoto", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Contractor;

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
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return $this
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }
    /**
     * @return File|null
     */
    public function getProfilePhoto(): ?File
    {
        return $this->profilePhoto;
    }

    /**
     * @param File|null $profilePhoto
     */
    public function setProfilePhoto(?File $profilePhoto)
    {
        $this->profilePhoto = $profilePhoto;
    }

    /**
     * @return Contractor
     */
    public function getContractor(): Contractor
    {
        return $this->Contractor;
    }

    /**
     * @param Contractor $Contractor
     * @return $this
     */
    public function setContractor(Contractor $Contractor): self
    {
        $this->Contractor = $Contractor;

        return $this;
    }
}
