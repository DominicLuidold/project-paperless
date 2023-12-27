<?php

declare(strict_types=1);

namespace Framework\Infrastructure\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Framework\Domain\Model\TranslationValueObject;
use Fusonic\DDDExtensions\Doctrine\Types\ValueObjectType;
use Fusonic\DDDExtensions\Domain\Model\ValueObject;

/**
 * @extends ValueObjectType<TranslationValueObject>
 */
final class TranslationValueObjectType extends ValueObjectType
{
    public const NAME = 'translation_vo';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        return parent::serialize($value, static fn (TranslationValueObject $object): array => self::fromObject($object));
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ValueObject
    {
        return parent::deserialize($value, static fn (array $data): TranslationValueObject => self::toObject($data));
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        // Add comment to prevent Doctrine from always detecting changes that need to be applied to the schema.
        return true;
    }

    /**
     * @param array<string, string> $data
     */
    public static function toObject(array $data): TranslationValueObject
    {
        return TranslationValueObject::create($data);
    }

    /**
     * @return array<string, string>
     */
    public static function fromObject(TranslationValueObject $object): array
    {
        return $object->getValues();
    }
}
