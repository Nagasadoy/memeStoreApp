<?php

namespace App\Message\CreateOrEditTag;

use App\Entity\Tag\DTO\EditTagDTO;
use App\Service\TagService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateOrEditTagMessageHandler
{
    public function __construct(private readonly TagService $tagService)
    {
    }

    public function __invoke(CreateOrEditTagMessage $message)
    {
        $id = $message->getId();
        $name = $message->getName();

        if (null === $id) {
            $this->tagService->create($name);
        } else {
            $this->tagService->edit(new EditTagDTO($name, $id));
        }
    }
}
