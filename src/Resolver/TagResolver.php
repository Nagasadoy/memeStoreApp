<?php

namespace App\Resolver;

use App\Entity\Tag\DTO\CreateTagDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TagResolver extends BaseResolver implements ValueResolverInterface
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @var CreateTagDTO $argumentType */
        $argumentType = $argument->getType();

        if (CreateTagDTO::class != $argumentType) {
            return [];
        }
        $dto = $argumentType::fromRequest($request);
        $this->validateEntity($dto, $this->validator);

        return [$dto];
    }
}
