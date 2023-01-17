<?php

namespace App\Controller;

use App\Entity\User\User;
use App\Normalizer\MemeNormalizer;
use App\Normalizer\Tag2Normalizer;
use App\Normalizer\TagNormalizer;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

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
    public function getMemes(UploaderHelper $uploaderHelper): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $memes = $user->getMemes();

        // вот такой костыль, чтобы получать ссылки
        foreach ($memes as $meme) {
            $link = $uploaderHelper->asset($meme->getMemeFile());
            $meme->setFileLink($link);
        }


        return $this->json(
            ['memes' => $memes],
            Response::HTTP_OK,
            [],
            ['groups' => ['meme:main', 'tag:only']]
        );
    }

    // #[Route('/logout', methods: ['POST'])]
    // public function logout(UserService $userService): Response
    // {
    //    $userService->logout();
    //    return new Response();
    // }
}
