<?php

namespace App\Message\CreateOrEditTag;

use App\Entity\Tag\DTO\EditTagDTO;
use App\Service\TagService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsMessageHandler]
class CreateOrEditTagMessageHandler
{
    public function __construct(
        private readonly TagService $tagService,
        private readonly DenormalizerInterface $denormalizer
    ) {}

    public function __invoke(CreateOrEditTagMessage $message)
    {
        $id = $message->getId();
        $name = $message->getName();

        if (null === $id) {
            $this->tagService->create($name);
        } else {
            $dto = $this->denormalizer->denormalize(['id' => $id, 'name' => $name], EditTagDTO::class);
            $this->tagService->edit($dto);
        }
    }
}
