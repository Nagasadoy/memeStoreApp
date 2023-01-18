<?php

namespace App\EventListener;

use App\Exception\ValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $code = $exception instanceof \DomainException ? 400 : 500;

        $message = $exception->getMessage();

        $data['message'] = $message;

        if ($exception instanceof ValidationException) {
            $errors = [];

            foreach ($exception->getErrors() as $error) {
                $errors[] = $error->getMessage();
            }

            $data['errors'] = $errors;
        }

        $response = new JsonResponse($data, $code);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
