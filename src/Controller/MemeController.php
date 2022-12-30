<?php

namespace App\Controller;

use App\Service\MemeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

//#[isGranted('ROLE_USER')]
#[Route('/meme', name: 'meme_')]
class MemeController extends AbstractController
{
    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request, MemeService $memeService): Response
    {
        $content = $request->toArray();
        $name = $content['name'];
        $fileName = $content['fileName'];
        $meme = $memeService->create($name, $fileName);

        return $this->json([
            'id' => $meme->getId(),
        ]);
    }
}
