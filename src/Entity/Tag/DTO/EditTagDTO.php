<?php

namespace App\Entity\Tag\DTO;

use App\Entity\Tag\Tag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class EditTagDTO
{
    private int $id;
    #[Assert\Length(
        max: 10,
        maxMessage: 'Название не может быть длиннее 10 символов'
    )]
    private string $name;

    public function __construct(string $name, int $id)
    {
        $this->name = $name;
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
