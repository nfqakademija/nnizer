<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoverPhotoRepository")
 * @Vich\Uploadable()
 */
class CoverPhoto implements Serializable
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
     * @Groups({"frontPage"})
     */
    private $filename;

    /**
     * @Vich\UploadableField(mapping="contractorsCover", fileNameProperty="filename")
     *
     * @var File
     */
    private $coverPhoto;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Contractor", inversedBy="coverPhoto", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Contractor;

    /**
     * @return int
     */
    public function getId(): int
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
     * @return Contractor|null
     */
    public function getContractor(): ?Contractor
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

    /**
     * @param File|null $coverPhoto
     * @throws \Exception
     */
    public function setCoverPhoto(?File $coverPhoto = null): void
    {
        $this->coverPhoto = $coverPhoto;
        if ($coverPhoto) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return File|null
     */
    public function getCoverPhoto(): ?File
    {
        return $this->coverPhoto;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->filename
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            ) = unserialize($serialized);
    }
}
