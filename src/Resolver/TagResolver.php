<?php

namespace App\Resolver;

use App\Entity\Tag\DTO\CreateTagDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TagResolver implements ValueResolverInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @var CreateTagDTO $argumentType */
        $argumentType = $argument->getType();

        if (CreateTagDTO::class != $argumentType) {
            return [];
        }
        $content = $request->toArray();

        $dto = $argumentType::fromString($content);

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $messages = '';
            foreach ($errors as $error) {
                $messages .= $error->getMessage().' ';
            }
            throw new \DomainException('Не пройдена валидация! '.$messages);
        }

        return [$dto];
    }
}
