<?php

namespace App\Normalizer;

use App\Entity\Meme\Meme;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpCache\StoreInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class MemeNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;
    public function __construct(
        private readonly UploaderHelper $uploaderHelper,
        private readonly RequestStack $requestStack,
        private readonly StorageInterface $storage
    )
    {
    }

    /**
     * @param Meme $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $url = $this->requestStack->getCurrentRequest()->getUriForPath($this->storage->resolveUri($object, 'file'));
        return [
            'id' => $object->getId(),
            'userMemeName' => $object->getUserMemeName(),
            'fileLink' => $url,
            'tags' => $this->normalizer->normalize($object->getTags(), null, $context)
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof Meme;
    }
}