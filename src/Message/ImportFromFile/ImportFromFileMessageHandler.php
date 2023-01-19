<?php

namespace App\Message\ImportFromFile;

use App\Message\CreateOrEditTag\CreateOrEditTagMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ImportFromFileMessageHandler
{
    public function __construct(private readonly MessageBusInterface $bus)
    {
    }

    public function __invoke(ImportFromFileMessage $message): void
    {
        $this->parseFile($message->getFilePath());
    }

    private function parseFile(string $filePath): void
    {
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                sleep(5);
                if ('' === $data[0]) {
                    $id = null;
                } else {
                    $id = (int) $data[0];
                }
                $name = $data[1];
                $message = new CreateOrEditTagMessage($name, $id);
                $this->bus->dispatch($message);
            }
            fclose($handle);
        }

        unlink($filePath);
    }
}
