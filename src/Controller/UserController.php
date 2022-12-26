<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('api/user', name: 'api_user_')]
class UserController extends AbstractController
{
    #[Route('/registration', name: 'registration', methods: ['POST'])]
    public function registration(
        UserRepository $userRepository,
        Request $request,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
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
            'user' => $user->getId()
        ]);
    }

    /**
     * Метод для получения пользователя по логину и паролю
     */
    #[Route('/login', name: 'login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user, JWTTokenManagerInterface $JWTManager): Response
    {

        if (null === $user) {
            return $this->json([
                'message' => 'Неверные данные'
            ]);
        }

        $token = $JWTManager->create($user);

        return $this->json([
            'token' => $token,
            'user' => $user->getUserIdentifier()
        ]);
    }

    #[IsGranted('PUBLIC_ACCESS')]
    #[Route('/action', name: 'action', methods: ['GET'])]
    public function action(): Response
    {
        return $this->json([
            'action' => 'action'
        ]);
    }

    #[Route('/get-random-int', name:'random_int', methods: ['GET'])]
    public function getRandomInt(): Response
    {
        $user = $this->getUser();

        return $this->json([
            'rnd' => rand(1,100),
            'user' => $user->getUserIdentifier()
        ]);
    }
}