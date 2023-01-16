<?php

namespace App\Controller;

use App\Entity\User\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('api/user', name: 'api_user_')]
class UserController extends AbstractController
{
    /**
     * Регистрация пользователя.
     */
    #[Route('/registration', name: 'registration', methods: ['POST'])]
    public function registration(
        UserRepository $userRepository,
        Request $request,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $content = $request->toArray();

        $email = $content['email'] ?? null;
        $plainPassword = $content['password'] ?? null;

        if (null === $email || null === $plainPassword) {
            throw new \DomainException('Не переданы данные для запроса');
        }

        $user = new User($email);
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plainPassword
        );
        $user->setPassword($hashedPassword);
        $userRepository->save($user, true);

        return $this->json([
            'user' => $user->getId(),
        ]);
    }

    #[isGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/memes')]
    public function getMemes(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $memes = $user->getMemes();

        $x = 1;

        return $this->json(
            ['memes' => $memes],
            Response::HTTP_OK,
            [],
            ['groups' => ['meme:main', 'tag:main']]
        );
    }

    #[Route('/remove-meme', name: 'remove_user_meme', methods: ['POST'])]
    public function removeUserMeme(Request $request, UserService $userService): Response
    {
        $content = $request->toArray();
        $memeId = $content['memeId'];
        $userService->removeUserMeme($memeId);

        return new Response();
    }

    // #[Route('/logout', methods: ['POST'])]
    // public function logout(UserService $userService): Response
    // {
    //    $userService->logout();
    //    return new Response();
    // }
}
