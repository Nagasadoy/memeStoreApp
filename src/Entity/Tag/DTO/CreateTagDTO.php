<?php

namespace App\Entity\Tag\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateTagDTO
{
    #[Assert\Length(
        min: 2,
        max: 10,
        minMessage: 'Название не может быть короче 2 символов',
        maxMessage: 'Название не может быть длиннее 10 символов'
    )]
    private string $name;

    // #[Assert\Positive(message: 'Значение score должно быть положительным')]
    // private int $score;

    public function __construct(string $name/* , int $score */)
    {
        $this->name = $name;
        // $this->score = $score;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
