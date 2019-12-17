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
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"frontPage"})
     */
    private $filename;

    /**
     * @Vich\UploadableField(mapping="contractorsCover", fileNameProperty="filename")
     *
     * @var File|string
     */
    private $coverPhoto;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Contractor", inversedBy="coverPhoto", cascade={"persist"})
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
    public function setFilename(?string $filename): self
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

    public function serialize()
    {
        $this->coverPhoto = base64_encode($this->coverPhoto);
    }

    public function unserialize($serialized)
    {
        $this->coverPhoto = base64_decode($this->coverPhoto);
    }
}
