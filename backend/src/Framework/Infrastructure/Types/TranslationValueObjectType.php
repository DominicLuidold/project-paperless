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
    public const string NAME = 'translation_vo';

    #[\Override]
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        return parent::serialize($value, static fn (TranslationValueObject $object): array => self::fromObject($object));
    }

    #[\Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ValueObject
    {
        return parent::deserialize($value, static fn (array $data): TranslationValueObject => self::toObject($data));
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
