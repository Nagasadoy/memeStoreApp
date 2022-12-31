<?php

namespace App\Entity;

use App\Repository\MemeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MemeRepository::class)]
class Meme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('combination:main')]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'memes', targetEntity: Combination::class)]
    private Collection $combinations;

    #[ORM\Column(length: 255)]
    #[Groups('combination:main')]
    private ?string $fileName = null;

    public function __construct(string $name, string $fileName)
    {
        $this->name = $name;
        $this->fileName = $fileName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
