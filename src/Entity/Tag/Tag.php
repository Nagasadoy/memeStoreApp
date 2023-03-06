<?php

namespace App\Entity\Tag;

use App\Entity\Meme\Meme;
use App\Entity\User\User;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\UniqueConstraint(
    name: 'name_tag_unique',
    columns: ['name']
)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tag:main', 'meme:create'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tag:main', 'meme:create', 'tag:only'])]
    #[Assert\Length(
        max: 50,
        maxMessage: 'Название не может быть длиннее 50 символов'
    )]
    private string $name;

    #[ORM\ManyToOne]
    private User $user;


    #[ORM\ManyToMany(targetEntity: Meme::class, inversedBy: 'tags')]
    private Collection $memes;

    public function __construct(string $name, User $user)
    {
        $this->name = $name;
        $this->user = $user;
        $this->memes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getName(): string
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
