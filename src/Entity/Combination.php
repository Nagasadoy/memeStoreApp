<?php

namespace App\Entity;

use App\Repository\CombinationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CombinationRepository::class)]
class Combination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Meme::class, inversedBy: 'combinations')]
    private array $memes;

    #[ORM\ManyToOne(targetEntity: Tag::class, inversedBy: 'combinations')]
    private array $tags;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'combinations')]
    private array $users;

    public function getId(): ?int
    {
        return $this->id;
    }

}
