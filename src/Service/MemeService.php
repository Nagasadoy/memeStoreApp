<?php

namespace App\Service;

use App\DTO\FilterMemeByTagDTO;
use App\Entity\Meme\DTO\CreateMemeDTO;
use App\Entity\Meme\Meme;
use App\Entity\Tag\DTO\AddTagDTO;
use App\Entity\User\User;
use App\Repository\MemeFileRepository;
use App\Repository\MemeRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class MemeService
{
    public function __construct(
        private readonly MemeRepository $memeRepository,
        private readonly MemeFileRepository $memeFileRepository,
        private readonly TagRepository $tagRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
    ) {
    }

    public function createMeme(CreateMemeDTO $createMemeDTO): Meme
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if (null === $user) {
            throw new \DomainException('Это действие недоступно неавторизованным пользователям!');
        }

        $memeFileId = $createMemeDTO->getMemeFileId();
        $memeFile = $this->memeFileRepository->find($memeFileId);

        if (null === $memeFile) {
            throw new \DomainException('Не удалось найти файл по указанному id='.$memeFileId);
        }
        $userMemeName = $createMemeDTO->getUserMemeName();

        $meme = new Meme($user, $memeFile, $userMemeName);
        $user->addMeme($meme);
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
