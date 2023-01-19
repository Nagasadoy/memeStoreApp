<?php

namespace App\Message\CreateOrEditTag;

class CreateOrEditTagMessage
{
    public function __construct(private string $name, private ?int $id = null)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
