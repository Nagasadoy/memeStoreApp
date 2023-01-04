<?php

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;

class TagService
{
    public function __construct(private readonly TagRepository $tagRepository)
    {
    }

    public function create(string $name): Tag
    {
        $tag = new Tag($name);
        $this->tagRepository->save($tag, true);

        return $tag;
    }
}