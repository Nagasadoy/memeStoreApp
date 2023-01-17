<?php

namespace App\Entity\Meme;

use App\Entity\Tag\Tag;
use App\Entity\User\User;
use App\Repository\MemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MemeRepository::class)]
#[ORM\UniqueConstraint(
    name: 'user_meme_unique',
    columns: ['user_id', 'meme_file_id', 'user_meme_name']
)]
#[UniqueEntity(['user', 'memeFile', 'userMemeName'])]
class Meme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['meme:main', 'meme:create'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'memes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'memes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MemeFile $memeFile = null;

    #[ORM\Column(length: 255)]
    #[Groups(['meme:main', 'meme:create'])]
    private string $userMemeName;

    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'memes', fetch: 'EAGER')]
    #[Groups(['meme:main', 'meme:create'])]
    private Collection $tags;

    #[Groups(['meme:main', 'meme:create'])]
    private ?string $fileLink;

    public function __construct(User $user, MemeFile $memeFile, string $userMemeName)
    {
        $this->user = $user;
        $this->memeFile = $memeFile;
        $this->userMemeName = $userMemeName;
        $this->tags = new ArrayCollection();

        $this->fileLink = 'gegege';
    }

    public function setFileLink(?string $fileLink): void
    {
        $this->fileLink = $fileLink;
    }

    public function getFileLink(): ?string
    {
        return $this->fileLink;
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

    public function getMemeFile(): ?MemeFile
    {
        return $this->memeFile;
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
