<?php

namespace App\Message\ImportFromFile;

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
