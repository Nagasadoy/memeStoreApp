<?php

namespace App\Resolver;

use App\Entity\Meme\DTO\CreateMemeDTO;
use App\Entity\User\User;
use App\Repository\MemeFileRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MemeResolver extends BaseResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly MemeFileRepository $memeFileRepository,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @var CreateMemeDTO $argumentType */
        $argumentType = $argument->getType();

        if (CreateMemeDTO::class != $argumentType) {
            return [];
        }

        $content = $request->toArray();

        $userMemeName = $content['userMemeName'] ??
            throw new \DomainException('Не передан обязательный параметр запроса - userMemeName');

        $memeFileId = $content['memeFileId'] ??
            throw new \DomainException('Не передан обязательный параметр запроса - memeFileId');

        /** @var User $user */
        $user = $this->security->getUser();

        if (null === $user) {
            throw new AccessException('Действие доступно только авторизованным пользователям');
        }

        $memeFile = $this->memeFileRepository->find($memeFileId);

        if (null === $memeFile) {
            throw new \DomainException('Не удалось найти файл по указанному id='.$memeFileId);
        }

        $dto = new CreateMemeDTO($user, $memeFile, $userMemeName);

        $this->validateEntity($dto, $this->validator);

        return [$dto];
    }
}
