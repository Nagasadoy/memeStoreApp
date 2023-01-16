<?php

namespace App\Entity\Tag\DTO;

use App\Entity\Meme\Meme;

class AddTagDTO
{
    public function __construct(
        private readonly Meme $meme,
        private readonly array $tags
    ) {
    }

    public function getMeme(): Meme
    {
        return $this->meme;
    }

    public function getTags(): array
    {
        return $this->tags;
    }
}
