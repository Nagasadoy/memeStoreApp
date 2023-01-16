<?php

namespace App\Controller;

use App\Entity\Tag\DTO\CreateTagDTO;
use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tag', name: 'tag_')]
class TagController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function create(CreateTagDTO $createTagDTO, TagService $tagService): Response
    {
        $tag = $tagService->create($createTagDTO->getName());

        return $this->json(
            $tag,
            Response::HTTP_OK,
            [],
            ['groups' => ['tag:main']]
        );
    }
}
