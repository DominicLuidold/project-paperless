<?php

declare(strict_types=1);

namespace Framework\Infrastructure\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Framework\Domain\Id\UuidEntityId;
use Symfony\Bridge\Doctrine\Types\AbstractUidType;
use Symfony\Component\Uid\Uuid;

/**
 * The code of this class has been copied over from {@see AbstractUidType} and was adjusted to fit the needs of the
 * custom {@see UuidEntityId} implementation, also based on the {@see Uuid} class.
 */
abstract class UuidEntityIdType extends Type
{
    /**
     * @return class-string
     */
    abstract protected function getTypeClass(): string;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if ($this->hasNativeGuidType($platform)) {
            return $platform->getGuidTypeDeclarationSQL($column);
        }

        return $platform->getBinaryTypeDeclarationSQL([
            'length' => 16,
            'fixed' => true,
        ]);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if ($value instanceof UuidEntityId) {
            return $this->hasNativeGuidType($platform) ? $value->getValue()->toRfc4122() : $value->getValue()->toBinary();
        }

        if (!\is_string($value)) {
            $this->throwInvalidType($value);
        }

        try {
            $uuid = Uuid::fromString($value);

            $typeClass = $this->getTypeClass();
            /** @var UuidEntityId $object */
            $object = new $typeClass($uuid);

            return (string) $object;
        } catch (\InvalidArgumentException $e) {
            $this->throwValueNotConvertible($value, $e);
        }
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): UuidEntityId
    {
        $typeClass = $this->getTypeClass();

        if ($value instanceof Uuid || null === $value) {
            /** @var UuidEntityId $object */
            $object = new $typeClass($value);

            return $object;
        }

        if (!\is_string($value)) {
            $this->throwInvalidType($value);
        }

        try {
            $uuid = Uuid::fromString($value);

            /** @var UuidEntityId $object */
            $object = new $typeClass($uuid);

            return $object;
        } catch (\InvalidArgumentException $e) {
            $this->throwValueNotConvertible($value, $e);
        }
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        // Add comment to prevent Doctrine from always detecting changes that need to be applied to the schema.
        return true;
    }

    private function hasNativeGuidType(AbstractPlatform $platform): bool
    {
        // Compatibility with DBAL < 3.4
        // @phpstan-ignore-next-line
        $method = method_exists($platform, 'getStringTypeDeclarationSQL')
            ? 'getStringTypeDeclarationSQL'
            : 'getVarcharTypeDeclarationSQL';

        // @phpstan-ignore-next-line
        return $platform->getGuidTypeDeclarationSQL([]) !== $platform->$method(['fixed' => true, 'length' => 36]);
    }

    private function throwInvalidType(mixed $value): never
    {
        throw ConversionException::conversionFailedInvalidType(
            value: $value,
            toType: self::getTypeRegistry()->lookupName($this),
            possibleTypes: ['null', 'string', Uuid::class]
        );
    }

    private function throwValueNotConvertible(mixed $value, \Throwable $previous): never
    {
        throw ConversionException::conversionFailed(
            value: $value,
            toType: self::getTypeRegistry()->lookupName($this),
            previous: $previous
        );
    }
}
