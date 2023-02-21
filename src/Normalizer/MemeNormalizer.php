<?php

namespace App\Normalizer;

use App\Entity\Meme\Meme;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class MemeNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;
    public function __construct(private readonly UploaderHelper $uploaderHelper)
    {
    }

    /**
     * @param Meme $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'userMemeName' => $object->getUserMemeName(),
            'fileLink' => $this->uploaderHelper->asset($object->getFile()),
            'tags' => $this->normalizer->normalize($object->getTags(), null, $context)
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof Meme;
    }
}