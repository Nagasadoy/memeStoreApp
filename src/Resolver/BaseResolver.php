<?php

namespace App\Resolver;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseResolver
{
    public function validateEntity(mixed $object, ValidatorInterface $validator): void
    {
        $errors = $validator->validate($object);

        if (count($errors) > 0) {
            $messages = '';
            foreach ($errors as $error) {
                $messages .= $error->getMessage().' ';
            }
            throw new \DomainException('Не пройдена валидация! '.$messages);
        }
    }
}