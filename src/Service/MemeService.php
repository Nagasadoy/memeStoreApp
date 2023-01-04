<?php

namespace App\Service;

use App\Entity\Meme;
use App\Entity\MemeFile;
use App\Entity\User;
use App\Repository\MemeFileRepository;
use App\Repository\MemeRepository;
use App\Repository\TagRepository;

class MemeService
{
    public function __construct(
        private readonly MemeFileRepository $memeFileRepository,
        private readonly MemeRepository     $memeRepository,
        private readonly TagRepository      $tagRepository,
    )
    {
    }

    public function createMemeFile(string $commonName, string $fileName): MemeFile
    {
        $memeFile = new MemeFile($commonName, $fileName);
        $this->memeFileRepository->save($memeFile, true);
        return $memeFile;
    }

    public function createMeme(User $user, string $memeFileId, string $userMemeFile): Meme
    {
        $memeFile = $this->memeFileRepository->find($memeFileId);

        if (null === $memeFile) {
            throw new \DomainException('Не удалось найти файл по этому id ' . $memeFileId);
        }

        $meme = new Meme($user, $memeFile, $userMemeFile);
        $this->memeRepository->save($meme, true);

        return $meme;
    }

    public function addTags(string $memeId, array $tagIds)
    {
        $meme = $this->memeRepository->find($memeId);
        $tags = $this->tagRepository->findByArrayIds($tagIds);

        array_map(static function ($tag) use ($meme) {
            $meme->addTag($tag);
        }, $tags);

        $this->memeRepository->save($meme, true);
    }
}