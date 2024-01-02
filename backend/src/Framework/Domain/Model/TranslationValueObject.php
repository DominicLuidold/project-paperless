<?php

declare(strict_types=1);

namespace Framework\Domain\Model;

use Fusonic\DDDExtensions\Domain\Model\ValueObject;
use Fusonic\DDDExtensions\Domain\Validation\Assert;

final class TranslationValueObject extends ValueObject
{
    /**
     * @var string[]
     */
    public const array SUPPORTED_LOCALES = ['de', 'en'];

    /**
     * @var array<string, string>
     */
    private readonly array $values;

    /**
     * @param array<string, string> $values
     */
    private function __construct(array $values)
    {
        Assert::that(self::class, 'values', $values)->minCount(1);
        foreach ($values as $locale => $value) {
            Assert::lazy(self::class)
                ->that($locale, 'values.locale')->choice(self::SUPPORTED_LOCALES)
                ->that($value, sprintf('values.%s.value', $locale))->notBlank()
                ->verifyNow();
        }

        ksort($values);
        $this->values = $values;
    }

    /**
     * @param array<string, string> $values
     */
    public static function create(array $values): self
    {
        return new self($values);
    }

    /**
     * @return string[]
     */
    public function getLocales(): array
    {
        return array_keys($this->values);
    }

    /**
     * @return array<string, string>
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function getValueForLocale(string $locale): ?string
    {
        $locale = strtolower($locale);

        return $this->values[$locale] ?? null;
    }

    #[\Override]
    public function equals(ValueObject $object): bool
    {
        return $object instanceof self
            && $this->values === $object->values;
    }
}
