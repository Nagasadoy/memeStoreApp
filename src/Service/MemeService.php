<?php

namespace App\Service;

use App\Entity\Meme\DTO\CreateMemeDTO;
use App\Entity\Meme\Meme;
use App\Entity\User\User;
use App\Repository\MemeFileRepository;
use App\Repository\MemeRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;

class MemeService
{
    public function __construct(
        //private readonly MemeFileRepository $memeFileRepository,
        private readonly MemeRepository $memeRepository,
        private readonly TagRepository $tagRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function createMeme(CreateMemeDTO $createMemeDTO/* User $user, string $memeFileId, string $userMemeName */): Meme
    {
        $user = $createMemeDTO->getUser();
        $memeFile = $createMemeDTO->getMemeFile();
        $userMemeName = $createMemeDTO->getUserMemeName();

        $meme = new Meme($user, $memeFile, $userMemeName);
        $user->addMeme($meme);
        $this->entityManager->flush();

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
