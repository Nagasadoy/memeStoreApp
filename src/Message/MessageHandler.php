<?php

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class MessageHandler
{
    public function __invoke(Message $message)
    {
        echo 'Обработка сообщения из очереди' . PHP_EOL;
    }
}