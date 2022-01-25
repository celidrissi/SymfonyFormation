<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class RestaurantrNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($restaurant, string $format = null, array $context = [])
    {
        // ->normalize can return an Object or the value returned by the CIRCULAR_REFERENCE_HANDLER. Both should be handled
        $restaurantArray = $this->normalizer->normalize($restaurant, $format, $context);
        if (!is_array($restaurantArray)) {
            // Handle circular reference value.
            return $restaurantArray;
        }

        if (isset($context["action"]) && in_array("list", $context["action"])) {
            // Option1 : Only display id, name when listing mode used
            return [
                $restaurantArray["id"],
                $restaurantArray["name"]
            ];

            // Option 2: Remove properties you do not want.
            // unset($restaurant["id"])
        }

        return $restaurantArray;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof \App\Entity\Restaurant;
    }

    // public function hasCacheableSupportsMethod(): bool
    // {
    //     return true;
    // }
}
