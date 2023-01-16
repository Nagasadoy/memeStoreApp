<?php

namespace App\Entity\Tag\DTO;

use App\Entity\Tag\Tag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTagDTO
{
    #[Assert\Length(
        max: 10,
        maxMessage: 'Название не может быть длиннее 10 символов'
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

    public static function fromRequest(Request $request): self
    {
        $requestContent = $request->toArray();
        $name = $requestContent['name'] ?? throw new \DomainException('Не удалось получить имя тэга');

        return new self($name);
    }

    public function convertToEntity(): Tag
    {
        return new Tag($this->name);
    }
}
