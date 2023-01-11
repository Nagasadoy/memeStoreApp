<?php

namespace App\Entity;

use App\Repository\MemeFileRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Entity\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: MemeFileRepository::class)]
#[Vich\Uploadable]
class MemeFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $commonName = null;

    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[Vich\UploadableField(mapping: 'memes', fileNameProperty: 'fileName')]
    private File $file;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(mappedBy: 'memeFile', targetEntity: Meme::class, orphanRemoval: true)]
    private Collection $memes;

    public function __construct(string $commonName, string $fileName)
    {
        $this->fileName = $fileName;
        $this->commonName = $commonName;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getFile(): File
    {
        return $this->file;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if ($file) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommonName(): ?string
    {
        return $this->commonName;
    }

    public function setCommonName(string $commonName): self
    {
        $this->commonName = $commonName;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }
}
