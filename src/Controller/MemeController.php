<?php

namespace App\Controller;

use App\Entity\MemeFile;
use App\Entity\User;
use App\Service\MemeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/meme', name: 'meme_')]
class MemeController extends AbstractController
{
    #[Route('/file/create', name: 'file_create', methods: ['POST'])]
    public function createFileMeme(
        Request $request,
        MemeService $memeService,
        EntityManagerInterface $entityManager
    ): Response
    {

        /** @var File $file */
        $file = $request->files->get('file');

        $memeFile = new MemeFile($file->getFilename());
        $memeFile->setFile($file);

        $entityManager->persist($memeFile);
        $entityManager->flush();

        return $this->json([
        'id' => $memeFile->getId(),
    ]);
    }

    #[Route('/file/delete/{id}', name: 'file_delete', methods: ['POST'])]
    #[ParamConverter('memeFile', MemeFile::class)]
    public function removeFile(MemeFile $memeFile, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($memeFile);
        $removedId = $memeFile->getId();
        $entityManager->flush();
        return $this->json([
            'id' => $removedId,
            'message' => ' Removed successfully',
        ]);
    }

    #[isGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/create', name: 'create', methods: ['POST'])]
    public function createMeme(Request $request, MemeService $memeService): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $content = $request->toArray();
        $userMemeFile = $content['userMemeFile'];
        $memeFileId = $content['memeFileId'];

        $meme = $memeService->createMeme($user, $memeFileId, $userMemeFile);

        return $this->json([
            'id' => $meme->getId(),
        ]);
    }

    #[isGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/tag/add', name: 'add_tag', methods: ['POST'])]
    public function addTag(Request $request, MemeService $memeService): Response
    {
        $content = $request->toArray();
        $tagIds = $content['tagIds'];
        $memeId = $content['memeId'];

        $memeService->addTags($memeId, $tagIds);

        return new Response();
    }
}
