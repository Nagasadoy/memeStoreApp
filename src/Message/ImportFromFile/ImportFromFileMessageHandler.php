<?php

namespace App\Message\ImportFromFile;

use App\Message\CreateOrEditTag\CreateOrEditTagMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[AsMessageHandler]
class ImportFromFileMessageHandler
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly SerializerInterface $serializer
    )
    {
    }

    public function __invoke(ImportFromFileMessage $message): void
    {
        $this->parseFile($message->getFilePath());
    }

    private function parseFile(string $filePath): void
    {

        $headers = [];
        $result = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {

                if (count($headers) == 0) {
                    for ($i = 0; $i < count($data); ++$i) {
                        $headers[] = $data[$i];
                    }
                } else {
                    $currentRow = [];
                    for ($i = 0; $i < count($data); ++$i) {
                        $currentRow[$headers[$i]] = $data[$i];
                    }
                    $result[] = $currentRow;

                    if ('' === $data[0]) {
                        $id = null;
                    } else {
                        $id = (int)$data[0];
                    }
                    $name = $data[1];
                    $message = new CreateOrEditTagMessage($name, $id);
                    $envelope = new Envelope($message, [
                        new DelayStamp(5000)
                    ]);
                    $this->bus->dispatch($envelope);

                }
            }
            fclose($handle);
        }

        // Извлечение данных из csv файла с помощью сериалайзера

        // $encoders = [new CsvEncoder()];
        // $serializer = new Serializer([], $encoders);
        // $data = $serializer->decode(file_get_contents($filePath), 'csv');
        //
        // foreach ($data as $row) {
        //    $message = $serializer->denormalize($row, CreateOrEditTagMessage::class);
        //    $this->bus->dispatch($message);
        // }

        unlink($filePath);
    }
}
