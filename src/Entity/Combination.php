<?php

namespace App\Entity;

use App\Repository\CombinationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CombinationRepository::class)]
class Combination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Meme::class, inversedBy: 'combinations')]
    private Collection $memes;

    #[ORM\ManyToOne(targetEntity: Tag::class, inversedBy: 'combinations')]
    private Collection $tags;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'combinations')]
    private Collection $users;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct(Meme $meme, Tag $tag, User $user)
    {
        $this->tags = new ArrayCollection();
        $this->memes = new ArrayCollection();
        $this->users = new ArrayCollection();

        $this->tags->add($tag);
        $this->memes->add($meme);
        $this->users->add($user);
    }

}
