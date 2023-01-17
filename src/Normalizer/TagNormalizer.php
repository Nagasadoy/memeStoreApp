<?php

namespace App\Normalizer;

use App\Entity\Tag\Tag;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TagNormalizer implements NormalizerInterface, ContextAwareNormalizerInterface
{
    /**
     * @param Tag $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'tag' => 1
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Tag && (($context['normalizer'] ?? '') === self::class);
    }
}
