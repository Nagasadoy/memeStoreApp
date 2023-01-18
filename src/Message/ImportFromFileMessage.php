<?php

namespace App\Message;

use Symfony\Component\Messenger\MessageBusInterface;

class ImportFromFileMessage
{
    public function __construct(private string $filePath)
    {
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }
}
