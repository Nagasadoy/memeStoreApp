<?php

namespace App\Controller;

use App\Message\ImportFromFile\ImportFromFileMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

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
}
