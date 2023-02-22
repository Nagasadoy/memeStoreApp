<?php

namespace App\Controller;

use App\Attribute\FromRequest;
use App\Entity\Meme\Meme;
use App\Entity\Tag\DTO\AddTagDTO;
use App\Entity\User\User;
use App\Repository\MemeRepository;
use App\Service\MemeService;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Vich\UploaderBundle\Storage\StorageInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/api/meme', name: 'meme_')]
class MemeController extends AbstractController
{
    #[isGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/{id}', name: 'by_id', methods: ['GET'])]
    public function getMeme(int $id, MemeRepository $memeRepository, StorageInterface $storage, Request $request): Response
    {
        $meme = $memeRepository->find($id);

        if ($meme === null) {
            throw new BadRequestHttpException('Нет мема с таким id!');
        }

        $currentUser = $this->getUser();

        if ($meme->getUser() !== $currentUser) {
            throw new BadRequestHttpException('Пользователь не является владельцем этого мема!');
        }

        return $this->json($meme, Response::HTTP_OK, [], ['groups' => ['meme:main', 'tag:main']]);
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
/*        #[FromRequest] CreateMemeDTO $createMemeDTO,*/
        MemeService $memeService,
        StorageInterface $storage,
        Request $request
    ): Response {
        $file = $request->files->get('file');
        $userMemeName = $request->request->get('userMemeName');
        $tagIds = explode(',', $request->request->get('tagIds'));

        $meme = $memeService->createMeme($file, $userMemeName, $tagIds);

        $url = $request->getUriForPath($storage->resolveUri($meme, 'file'));

        return $this->json([
            'id' => $meme->getId(),
            'name' => $meme->getUserMemeName(),
            'url' => $url
        ]);
    }

    #[isGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/tag/add', name: 'add_tag', methods: ['POST'])]
    public function addTags(
        #[FromRequest] AddTagDTO $addTagDTO,
        MemeService $memeService,
        UploaderHelper $uploaderHelper,
    ): Response {
        $meme = $memeService->addTags($addTagDTO);
//        $meme->setFileLink($uploaderHelper->asset($meme->getFile()));
        return $this->json($meme, Response::HTTP_OK, [], ['groups' => ['meme:main', 'tag:main']]);
    }
}
