<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        //$code = match ($exception)

        $code = $exception instanceof \DomainException ? 400 : 500;

        $response = new JsonResponse([
            'message' => $exception->getMessage(),
        ], $code);

        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
    //
    //private function getCode(ExceptionEvent $exception): int
    //{
    //    if ($exception instanceof \DomainException) {
    //        return 400;
    //    }
    //
    //    if($exception instanceof AccessEx)
    //}
}
