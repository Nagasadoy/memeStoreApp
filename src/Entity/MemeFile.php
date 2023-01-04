<?php

namespace App\Entity;

use App\Repository\MemeFileRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemeFileRepository::class)]
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

    #[ORM\OneToMany(mappedBy: 'memeFile', targetEntity: Meme::class, orphanRemoval: true)]
    private Collection $memes;


    public function __construct(string $commonName, string $fileName)
    {
        $this->fileName = $fileName;
        $this->commonName = $commonName;
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
