<?php

namespace App\Service;

use App\Entity\Meme;
use App\Repository\MemeRepository;

class MemeService
{
    public function __construct(private readonly MemeRepository $memeRepository)
    {
    }

    public function create(string $name, string $fileName): Meme
    {
        $meme = new Meme($name, $fileName);
        $this->memeRepository->save($meme, true);
        return $meme;
    }
}