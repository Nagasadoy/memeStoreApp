<?php

namespace App\Entity\Meme\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateMemeDTO
{
    #[Assert\NotBlank]
    private int $memeFileId;

    #[Assert\Length(
        min: 1,
        max: 20,
        minMessage: 'Название не должно быть короче 1 символа',
        maxMessage: 'Название не должно быть больше 20 символов',
    )]
    private string $userMemeName;

    public function getMemeFileId(): int
    {
        return $this->memeFileId;
    }

    public function getUserMemeName(): string
    {
        return $this->userMemeName;
    }

    public function __construct(string $memeFileId, string $userMemeName)
    {
        $this->userMemeName = $userMemeName;
        $this->memeFileId = $memeFileId;
    }
}
