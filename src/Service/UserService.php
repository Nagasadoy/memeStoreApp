<?php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;

class UserService
{
    public function __construct(private readonly Security $security)
    {
    }

    public function logout(): void
    {
        $user = $this->security->getUser();
        $x = 1;
    }
}