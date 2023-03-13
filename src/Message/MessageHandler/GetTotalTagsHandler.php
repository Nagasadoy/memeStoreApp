<?php

namespace App\Message\MessageHandler;

use App\Message\Query\GetTotalTags;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

#[AsMessageHandler]
class GetTotalTagsHandler
{
    public function __invoke(GetTotalTags $getTotalTags)
    {
        return 50;
    }
}