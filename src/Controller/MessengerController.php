<?php

namespace App\Controller;

use App\Message\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/message', name: 'message_')]
class MessengerController extends AbstractController
{
    #[Route('/send', name: 'send', methods: ['POST'])]
    public function sendMessage(MessageBusInterface $bus)
    {
        $message = new Message('строка, которую мы прочитали из файла');
        $bus->dispatch($message);
        return $this->json(['ok' => 'ok']);
    }
}