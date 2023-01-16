<?php

namespace App\Service;

use App\Entity\Meme\Meme;
use App\Entity\User\User;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

class UserService
{
    public function __construct(
        private readonly Security $security,
        private readonly UserRepository $userRepository
    ) {
    }

    public function logout(): void
    {
        $user = $this->security->getUser();
        $x = 1;
    }

    public function removeUserMeme(User $user, Meme $meme): void
    {
        $user->removeMeme($meme);
        $this->userRepository->save($user, true);
    }
}
