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

        $data = $this->collectData($request);

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

    private function validateEntity(mixed $object): void
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

    private function collectData(Request $request): array
    {
        $routeParameters = $this->getRouteParams($request);

        $data = [];

        if ('json' === $request->getContentTypeFormat()) {
            $data = $request->toArray();
        }

        $data = array_merge($data, $request->query->all());

        return $this->mergeRequestData($data, $routeParameters);
    }

    protected function mergeRequestData(array $data, array $routeParameters): array
    {
        if (\count($keys = array_intersect_key($data, $routeParameters)) > 0) {
            throw new \DomainException(sprintf('Parameters (%s) used as route attributes can not be used in the request body or query parameters.', implode(', ', array_keys($keys))));
        }

        return array_merge($data, $routeParameters);
    }

    protected function getRouteParams(Request $request): array
    {
        $params = $request->attributes->get('_route_params', []);

        foreach ($params as $key => $param) {
            $value = filter_var($param, \FILTER_VALIDATE_INT, \FILTER_NULL_ON_FAILURE);
            $params[$key] = $value ?? $param;
        }

        return $params;
    }
}
