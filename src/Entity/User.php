<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('user:main')]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups('user:main')]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Meme::class, orphanRemoval: true)]
    private Collection $memes;

//    #[ORM\OneToMany(
//        mappedBy: 'user',
//        targetEntity: Combination::class,
//        cascade: ['remove', 'persist'],
//        orphanRemoval: true)
//    ]
//    private $combinations;

    public function __construct(string $email)
    {
        $this->email = $email;
        $this->combinations = new ArrayCollection();
        $this->memFile = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCombinations()
    {
        return $this->combinations;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return Collection<int, Meme>
     */
    public function getMemFile(): Collection
    {
        return $this->memes;
    }

//    public function addMemFile(Meme $memFile): self
//    {
//        if (!$this->memFile->contains($memFile)) {
//            $this->memFile->add($memFile);
//            $memFile->setUser($this);
//        }
//
//        return $this;
//    }
//
//    public function removeMemFile(Meme $memFile): self
//    {
//        if ($this->memFile->removeElement($memFile)) {
//            // set the owning side to null (unless already changed)
//            if ($memFile->getUser() === $this) {
//                $memFile->setUser(null);
//            }
//        }
//
//        return $this;
//    }
}
