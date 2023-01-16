<?php

namespace App\Resolver;

use App\Entity\Tag\DTO\AddTagDTO;
use App\Entity\User\User;
use App\Repository\MemeRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddTagDTOResolver extends BaseResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly MemeRepository     $memeRepository,
        private readonly TagRepository      $tagRepository,
        private readonly Security           $security,
    )
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (AddTagDTO::class != $argumentType) {
            return [];
        }

        /** @var User $user */
        $user = $this->security->getUser();

        if (null === $user) {
            throw new \DomainException('Это действие могут выполнять только авторизованные пользователи');
        }

        $content = $request->toArray();

        $memeId = $content['memeId'] ?? throw new \DomainException('Не переданы обязательные параметры запроса - memeId');
        $tagIds = $content['tagIds'] ?? throw new \DomainException('Не переданы обязательные параметры запроса - tagIds');

        if (!is_array($tagIds)) {
            throw new \DomainException('Параметр tagIds должен быть массивом');
        }

        $meme = $this->memeRepository->find($memeId);

        if (null === $meme) {
            throw new \DomainException('Нет мема с таким id=' . $memeId);
        }

        if (!$user->hasMeme($meme)) {
            throw new \DomainException('Только владелец мема может добавлять к нему тэги');
        }

        $tags = $this->tagRepository->findByArrayIds($tagIds);

        if (count($tags) !== count(array_unique($tagIds))) {

            //$uniqueIds = array_unique($tagIds);

            //foreach ($uniqueIds as $tagId) {
            //    foreach ($tags as $tag) {
            //        if ($tag->getId() === $tagId) {
            //            break 2;
            //        }
            //    }
            //}

            //$notFoundTags = [];

            throw new \DomainException('Не все тэги по указанным id удалось найти');
        }

        $dto = new AddTagDTO($meme, $tags);

        $this->validateEntity($dto, $this->validator);
        return [$dto];
    }
}
