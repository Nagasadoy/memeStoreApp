<?php

namespace App\Controller;

use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tag', name: 'tag_')]
class TagController extends AbstractController
{
    #[Route('/create', name:'create')]
    public function create(Request $request, TagService $tagService): Response
    {
        $content = $request->toArray();
        $name = $content['name'];
        $tag = $tagService->create($name);

        return $this->json([
            'id' => $tag->getId(),
        ]);
    }
}