<?php

namespace App\Controller;

use App\Message\ImportFromFile\ImportFromFileMessage;
use App\Message\Query\GetTotalTags;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/message', name: 'message_')]
class MessengerController extends AbstractController
{
    #[Route('/send', name: 'send', methods: ['POST'])]
    public function sendMessage(MessageBusInterface $bus, Request $request): Response
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('csv');
        $projectDir = $this->getParameter('app.project_dir');
        $file = $file->move($projectDir.'/public', $file->getFilename());

        $importFromFileMessage = new ImportFromFileMessage($projectDir.'/public/'.$file->getFilename());
        $bus->dispatch($importFromFileMessage);

        return $this->json(['message' => 'Сообщение импорта файла отправлено в очередь']);
    }

    #[Route('/query')]
    public function query(MessageBusInterface $queryBus): Response
    {
        $envelope = $queryBus->dispatch(new GetTotalTags());
        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);

        $tagsCount = $handled->getResult();

        return $this->json([
            'count' => $tagsCount
        ]);
    }
}
