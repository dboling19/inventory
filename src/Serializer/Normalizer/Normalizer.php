<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class Normalizer extends ObjectNormalizer
{
    public function __construct(private ObjectNormalizer $normalizer)
    {
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        return $this->normalizer->normalize($object, $format, $context);

    }
}
