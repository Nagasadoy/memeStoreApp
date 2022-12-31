<?php

namespace App\Entity;

use App\Repository\CombinationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CombinationRepository::class)]
class Combination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Meme::class, inversedBy: 'combinations')]
    #[Groups('combination:main')]
    private Meme $meme;

    #[ORM\ManyToOne(targetEntity: Tag::class, inversedBy: 'combinations')]
    #[Groups('combination:main')]
    private Tag $tag;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'combinations')]
    private User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct(Meme $meme, Tag $tag, User $user)
    {
        $this->meme = $meme;
        $this->tag = $tag;
        $this->user = $user;
    }

    /**
     * @return Meme
     */
    public function getMeme(): Meme
    {
        return $this->meme;
    }

    /**
     * @return Tag
     */
    public function getTag(): Tag
    {
        return $this->tag;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

}
