<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Meme::class, inversedBy: 'tags')]
    private Collection $memes;

    public function __construct()
    {
        $this->memes = new ArrayCollection();
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

    /**
     * @return Collection<int, Meme>
     */
    public function getMemes(): Collection
    {
        return $this->memes;
    }

    public function addMeme(Meme $meme): self
    {
        if (!$this->memes->contains($meme)) {
            $this->memes->add($meme);
        }

        return $this;
    }

    public function removeMeme(Meme $meme): self
    {
        $this->memes->removeElement($meme);

        return $this;
    }
}
