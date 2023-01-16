<?php

namespace App\Entity\Meme\DTO;

use App\Entity\Meme\MemeFile;
use App\Entity\User\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateMemeDTO
{
    private User $user;
    #[Assert\NotBlank]
    private MemeFile $memeFile;

    #[Assert\Length(
        min: 1,
        max: 20,
        minMessage: 'Название не должно быть короче 1 символа',
        maxMessage: 'Название не должно быть больше 20 символов',
    )]
    private string $userMemeName;

    public function __construct(User $user, MemeFile $memeFile, string $userMemeName)
    {
        $this->user = $user;
        $this->memeFile = $memeFile;
        $this->userMemeName = $userMemeName;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getMemeFile(): MemeFile
    {
        return $this->memeFile;
    }

    public function getUserMemeName(): string
    {
        return $this->userMemeName;
    }
}
