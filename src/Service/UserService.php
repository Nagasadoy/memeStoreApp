<?php

namespace App\Service;

use App\Entity\Combination;
use App\Entity\User;
use App\Repository\CombinationRepository;
use App\Repository\MemeRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

class UserService
{
    public function __construct(
        private readonly Security              $security,
        private readonly CombinationRepository $combinationRepository,
        private readonly MemeRepository        $memeRepository,
        private readonly TagRepository         $tagRepository,
        private readonly UserRepository        $userRepository
    )
    {
    }

    public function logout(): void
    {
        $user = $this->security->getUser();
        $x = 1;
    }

    public function createNewCombination(string $memeId, string $tagId): Combination
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $meme = $this->memeRepository->find($memeId);
        $tag = $this->tagRepository->find($tagId);

        if ($meme === null || $tag === null || $user === null) {
            throw new \Exception('Нет такого мема/тэга/пользователя');
        }

        $combination = new Combination($meme, $tag, $user);
        $user->addCombination($combination);
        $this->userRepository->save($user, true);
        return $combination;
    }

    public function removeCombination(string $memeId, string $tagId): void
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $meme = $this->memeRepository->find($memeId);
        $tag = $this->tagRepository->find($tagId);

        $this->combinationRepository->removeTag($tag, $meme, $user);
    }

    public function removeUserMeme(string $memeId): void
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $meme = $this->memeRepository->find($memeId);

        $this->combinationRepository->removeUserMeme($meme, $user);
    }
}