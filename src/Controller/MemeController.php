<?php

namespace App\Controller;

use App\Attribute\FromRequest;
use App\Entity\Meme\DTO\CreateMemeDTO;
use App\Entity\Meme\Meme;
use App\Entity\Meme\MemeFile;
use App\Entity\Tag\DTO\AddTagDTO;
use App\Entity\User\User;
use App\Repository\MemeFileRepository;
use App\Service\MemeService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Vich\UploaderBundle\Storage\StorageInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/api/meme', name: 'meme_')]
class MemeController extends AbstractController
{
    #[Route('/file/create', name: 'file_create', methods: ['POST'])]
    public function createFileMeme(
        Request $request,
        MemeService $memeService,
        EntityManagerInterface $entityManager
    ): Response {
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
        // По сути дела этот маршрут вообще не нужен, был добавлен, чтобы протестировать vich uploader
        // (что удаляются по-настоящему файлы, при удалении из БД)

        $entityManager->remove($memeFile);
        $removedId = $memeFile->getId();
        $entityManager->flush();

        return $this->json([
            'id' => $removedId,
            'message' => ' Removed successfully',
        ]);
    }

    #[isGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/remove/{id}', name: 'remove', methods: ['DELETE'])]
    #[ParamConverter('meme', Meme::class)]
    public function removeMeme(Meme $meme, UserService $userService)
    {
        /** @var User $user */
        $user = $this->getUser();
        $userService->removeUserMeme($user, $meme);

        return new Response('');
    }

    #[isGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/create', name: 'create', methods: ['POST'])]
    public function createMeme(
        #[FromRequest] CreateMemeDTO $createMemeDTO,
        MemeService $memeService,
        UploaderHelper $uploaderHelper
    ): Response {
        $meme = $memeService->createMeme($createMemeDTO);
        $meme->setFileLink($uploaderHelper->asset($meme->getMemeFile()));

        return $this->json($meme, Response::HTTP_OK, [], ['groups' => ['meme:create']]);
    }

    #[isGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/tag/add', name: 'add_tag', methods: ['POST'])]
    public function addTags(
        #[FromRequest] AddTagDTO $addTagDTO,
        MemeService $memeService,
        UploaderHelper $uploaderHelper,
    ): Response {
        $meme = $memeService->addTags($addTagDTO);
        $meme->setFileLink($uploaderHelper->asset($meme->getMemeFile()));
        return $this->json($meme, Response::HTTP_OK, [], ['groups' => ['meme:main', 'tag:main']]);
    }

    #[Route('/image/{id}', name: 'image', methods: ['GET'])]
    public function getImageById(int $id, Request $request, StorageInterface $storage, MemeFileRepository $memeFileRepository)
    {
        $meme = $memeFileRepository->findOneBy(['id' => $id]);

        $url = $request->getUriForPath($storage->resolveUri($meme, 'file'));

        return $this->json(['url' => $url]);
    }
}
