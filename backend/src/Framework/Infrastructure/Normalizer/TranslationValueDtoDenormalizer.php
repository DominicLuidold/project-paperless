<?php

declare(strict_types=1);

namespace Framework\Infrastructure\Normalizer;

use Framework\Domain\Model\TranslationValueDto;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final readonly class TranslationValueDtoDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): TranslationValueDto
    {
        return new TranslationValueDto($data);
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return \is_array($data) && is_a(object_or_class: $type, class: TranslationValueDto::class, allow_string: true);
    }

    /**
     * @return array<class-string, bool>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            TranslationValueDto::class => false,
        ];
    }
}
