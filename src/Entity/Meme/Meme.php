<?php

namespace App\Entity\Meme;

use App\Entity\Tag\Tag;
use App\Entity\User\User;
use App\Repository\MemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: MemeRepository::class)]
#[ORM\UniqueConstraint(
    name: 'user_meme_unique',
    columns: ['user_id', 'user_meme_name']
)]
#[UniqueEntity(['user', 'userMemeName'])]
#[Vich\Uploadable]
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

    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[Vich\UploadableField(mapping: 'memes', fileNameProperty: 'fileName')]
    private ?File $file = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(length: 255)]
    #[Groups(['meme:main', 'meme:create'])]
    private string $userMemeName;

    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'memes', fetch: 'EAGER')]
    #[Groups(['meme:main', 'meme:create'])]
    private Collection $tags;

    #[ORM\Column(length: 255)]
    private ?string $commonName = null;

    private string $fileLink;

    public function __construct(User $user, string $commonName, string $userMemeName)
    {
        $this->user = $user;
        $this->commonName = $commonName;
        $this->userMemeName = $userMemeName;
        $this->tags = new ArrayCollection();

        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
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

    public function getCommonName(): ?string
    {
        return $this->commonName;
    }

    public function getFile(): ?File
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

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }
//
//    public function getFileLink(): string
//    {
//        return $this->fileLink;
//    }
//
//    public function setFileLink(string $fileLink): void
//    {
//        $this->fileLink = $fileLink;
//    }
}
