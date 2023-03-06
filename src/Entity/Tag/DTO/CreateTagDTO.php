<?php

namespace App\Entity\Tag\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateTagDTO
{
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Название не может быть короче 2 символов',
        maxMessage: 'Название не может быть длиннее 20 символов'
    )]
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
