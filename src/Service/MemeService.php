<?php

namespace App\Service;

use App\DTO\FilterMemeByTagDTO;
use App\Entity\Meme\Meme;
use App\Entity\Tag\DTO\AddTagDTO;
use App\Entity\User\User;
use App\Repository\MemeRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class MemeService
{
    public function __construct(
        private readonly MemeRepository $memeRepository,
        private readonly TagRepository $tagRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
    ) {
    }

    public function createMeme(UploadedFile $file, string $userMemeName, array $tagIds): Meme
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if (null === $user) {
            throw new UnauthorizedHttpException('Это действие недоступно неавторизованным пользователям!');
        }

        $meme = $this->memeRepository->findMemeByNameAndUser($userMemeName, $user);
        if ($meme !== null) {
            throw new DomainException('Мем с таким именем уже существует!');
        }

        $tags = $this->tagRepository->findByArrayIds($tagIds);

        $commonName = $file->getFilename();

        $meme = new Meme($user, $commonName, $userMemeName);
        $meme->setFile($file);
        $user->addMeme($meme);

        foreach ($tags as $tag) {
            $meme->addTag($tag);
        }

        $this->entityManager->flush();
        return $meme;
    }

    public function addTags(AddTagDTO $addTagDTO): Meme
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if (null === $user) {
            throw new \DomainException('Это действие могут выполнять только авторизованные пользователи');
        }

        $memeId = $addTagDTO->getMemeId();
        $meme = $this->memeRepository->find($memeId);

        if (null === $meme) {
            throw new \DomainException('Нет мема с таким id=' . $memeId);
        }

        if (!$user->hasMeme($meme)) {
            throw new \DomainException('Только владелец мема может добавлять к нему тэги');
        }

        $tagIds = $addTagDTO->getTagIds();
        $tags = $this->tagRepository->findByArrayIds($tagIds);

        if (count($tags) !== count(array_unique($tagIds))) {
            throw new \DomainException('Не все тэги по указанным id удалось найти');
        }

        array_map(static function ($tag) use ($meme) {
            $meme->addTag($tag);
        }, $tags);

        $this->memeRepository->save($meme, true);

        return $meme;
    }

    public function getUserMemes(User $user, FilterMemeByTagDTO $filter)
    {
        return $this->memeRepository->getUserMemes($user, $filter);
    }
}
