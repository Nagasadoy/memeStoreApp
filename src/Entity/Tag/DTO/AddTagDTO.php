<?php

namespace App\Entity\Tag\DTO;

use App\Entity\Meme\Meme;

class AddTagDTO
{
    /** @var int[] */
    private readonly array $tagIds;
    private readonly int $memeId;

    public function __construct(int $memeId, array $tagIds)
    {
        $this->memeId = $memeId;
        $this->tagIds = $tagIds;
    }

    public function getTagIds(): array
    {
        return $this->tagIds;
    }

    public function getMemeId(): int
    {
        return $this->memeId;
    }
}
