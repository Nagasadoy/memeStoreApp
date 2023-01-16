<?php

namespace App\Service;

use App\Entity\Meme\Meme;
use App\Entity\User\User;
use App\Repository\MemeFileRepository;
use App\Repository\MemeRepository;
use App\Repository\TagRepository;

class MemeService
{
    public function __construct(
        private readonly MemeFileRepository $memeFileRepository,
        private readonly MemeRepository $memeRepository,
        private readonly TagRepository $tagRepository,
    ) {
    }

    public function createMeme(User $user, string $memeFileId, string $userMemeName): Meme
    {
        $memeFile = $this->memeFileRepository->find($memeFileId);

        if (null === $memeFile) {
            throw new \DomainException('Не удалось найти файл по этому id '.$memeFileId);
        }

        $meme = new Meme($user, $memeFile, $userMemeName);

        $user->
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
