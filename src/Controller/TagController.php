<?php

namespace App\Controller;

use App\Attribute\FromRequest;
use App\Entity\Tag\DTO\CreateTagDTO;
use App\Entity\Tag\DTO\EditTagDTO;
use App\Repository\TagRepository;
use App\Service\TagService;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TagController extends AbstractController
{
    #[isGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/api/tag/create', name: 'tag_create')]
    public function create(#[FromRequest] CreateTagDTO $createTagDTO, TagService $tagService): Response
    {
        $user = $this->getUser();

        if ($user === null) {
            throw new UnauthorizedHttpException('Только авторизованные пользователи могут создавать тэги!');
        }

        $tag = $tagService->create($createTagDTO->getName(), $user);

        return $this->json(
            $tag,
            Response::HTTP_OK,
            [],
            ['groups' => ['tag:main']]
        );
    }

    #[Route('/api/tag/{id}/edit', name: 'tag_edit')]
    public function edit(#[FromRequest] EditTagDTO $editTagDTO, TagService $tagService): Response
    {
        $tag = $tagService->edit($editTagDTO);
        return $this->json(
            $tag,
            Response::HTTP_OK,
            [],
            ['groups' => ['tag:main']]
        );
    }

    #[Route('/api/tags', name: 'tag_get_all')]
    public function getAll(TagRepository $tagRepository): Response
    {

        $user = $this->getUser();

        if ($user !== null) {
            $tags = $tagRepository->findAllByUser($user);
        } else {
            $tags = $tagRepository->findAll();
        }

        return $this->json($tags,
            Response::HTTP_OK,
            [],
            ['groups' => ['tag:main']]);
    }
}
