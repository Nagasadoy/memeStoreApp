<?php

namespace App\DTO;

class FilterMemeByTagDTO
{
    private ?string $tagName;

    private ?int $score;

    public function __construct(?string $tagName = null, ?int $score = null)
    {
        $this->tagName = $tagName;
        $this->score = $score;
    }

    public function getTagName(): ?string
    {
        return $this->tagName;
    }
}
