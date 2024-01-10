<?php

declare(strict_types=1);

namespace Framework\Infrastructure\Normalizer;

use Framework\Domain\Id\UuidEntityId;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Uid\Uuid;

final readonly class UuidEntityIdNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @param mixed|UuidEntityId $object
     */
    #[\Override]
    public function normalize(mixed $object, string $format = null, array $context = []): string
    {
        return $object->getValue()->toRfc4122();
    }

    /**
     * @param array<mixed> $context
     */
    #[\Override]
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof UuidEntityId;
    }

    #[\Override]
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): UuidEntityId
    {
        /** @var UuidEntityId $id */
        $id = new $type(Uuid::fromString($data));

        return $id;
    }

    /**
     * @param array<mixed> $context
     */
    #[\Override]
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return \is_string($data) && Uuid::isValid($data) && is_a($type, UuidEntityId::class, true);
    }

    /**
     * @return array<class-string, bool>
     */
    #[\Override]
    public function getSupportedTypes(?string $format): array
    {
        return [
            UuidEntityId::class => false,
        ];
    }
}
