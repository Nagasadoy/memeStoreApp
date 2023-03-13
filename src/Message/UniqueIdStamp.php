<?php

namespace App\Message;

use Symfony\Component\Messenger\Stamp\StampInterface;

class UniqueIdStamp implements StampInterface
{
    private string $uniqid;

    public function __construct()
    {
        $this->uniqid = uniqid();
    }

    public function getUniqueId(): string
    {
        return $this->uniqid;
    }
}