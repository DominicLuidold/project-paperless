<?php

declare(strict_types=1);

namespace Framework\Infrastructure\Normalizer;

use Framework\Domain\Model\TranslationValueObject;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class TranslationValueObjectNormalizer implements NormalizerInterface
{
    /**
     * @param mixed|TranslationValueObject $object
     *
     * @return array<string, string>
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return $object->getValues();
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof TranslationValueObject;
    }

    /**
     * @return array<class-string, bool>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            TranslationValueObject::class => true,
        ];
    }
}
