<?php

namespace App\Resolver;

use App\Attribute\FromRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ResolverDTO implements ValueResolverInterface
{
    public function __construct(
        private readonly DenormalizerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        // Если класс не помечен атрибутом, тогда пропускаем
        if (!$this->isSupportedArgument($argument)) {
            return [];
        }

        $data = $request->toArray();
        $className = $argument->getType();
        try {
            $dto = $this->serializer->denormalize($data, $className, JsonEncoder::FORMAT);
        } catch (ExceptionInterface $e) {
            $message = 'Произошла ошибка при попытке создать объект из реквеста! Подробнее: '.$e->getMessage();

            if ($e instanceof MissingConstructorArgumentsException) {
                $missingFields = $e->getMissingConstructorArguments();
                $message = 'Не передан обязательный параметр запроса: '.implode(',', $missingFields);
            }

            throw new \DomainException($message);
        }

        $this->validateEntity($dto);
        yield $dto;
    }

    private function isSupportedArgument(ArgumentMetadata $argument): bool
    {
        // Берем только классы, помеченные атрибутом
        if (0 == count($argument->getAttributes(FromRequest::class))) {
            return false;
        }

        return true;
    }

    protected function validateEntity(mixed $object): void
    {
        $errors = $this->validator->validate($object);

        if (count($errors) > 0) {
            $messages = '';
            foreach ($errors as $error) {
                $messages .= $error->getMessage().' ';
            }
            throw new \DomainException('Не пройдена валидация! '.$messages);
        }
    }
}
