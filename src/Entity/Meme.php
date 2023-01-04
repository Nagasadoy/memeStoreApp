<?php

namespace App\Entity;

use App\Repository\MemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MemeRepository::class)]
class Meme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('meme:main')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'memes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'memes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MemeFile $memeFile = null;

    #[ORM\Column(length: 255)]
    #[Groups('meme:main')]
    private string $userMemeName;

    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'memes')]
    #[Groups('meme:main')]
    private Collection $tags;

    public function __construct(User $user, MemeFile $memeFile, string $userMemeName)
    {
        $this->user = $user;
        $this->memeFile = $memeFile;
        $this->userMemeName = $userMemeName;

        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addMeme($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeMeme($this);
        }

        return $this;
    }

    public function getUserMemeName(): string
    {
        return $this->userMemeName;
    }
}
