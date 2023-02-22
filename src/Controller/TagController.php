<?php

namespace App\Controller;

use App\Attribute\FromRequest;
use App\Entity\Tag\DTO\CreateTagDTO;
use App\Entity\Tag\DTO\EditTagDTO;
use App\Repository\TagRepository;
use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/api/tag/create', name: 'tag_create')]
    public function create(#[FromRequest] CreateTagDTO $createTagDTO, TagService $tagService): Response
    {
        $tag = $tagService->create($createTagDTO->getName());

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
        $tags = $tagRepository->findAll();
        return $this->json($tags,
            Response::HTTP_OK,
            [],
            ['groups' => ['tag:main']]);
    }
}
