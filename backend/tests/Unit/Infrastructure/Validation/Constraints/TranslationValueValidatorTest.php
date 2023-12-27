<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Validation\Constraints;

use Framework\Domain\Model\TranslationValueDto;
use Framework\Infrastructure\Validation\Constraints\TranslationValue;
use Framework\Infrastructure\Validation\Constraints\TranslationValueValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<TranslationValueValidator>
 */
final class TranslationValueValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        return new TranslationValueValidator();
    }

    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new TranslationValue());

        $this->assertNoViolation();
    }

    /**
     * @param array<string, string> $values
     *
     * @dataProvider getValidValues
     */
    public function testValidValues(array $values): void
    {
        $translationValueDto = new TranslationValueDto($values);
        $this->validator->validate($translationValueDto, new TranslationValue());

        $this->assertNoViolation();
    }

    /**
     * @param array<string, string> $values
     *
     * @dataProvider getInvalidFormatValues
     */
    public function testInvalidFormatValues(array $values): void
    {
        $translationValueDto = new TranslationValueDto($values);
        $this->validator->validate($translationValueDto, new TranslationValue());

        $this->buildViolation('Invalid translation format. Correct example: `{\'de\': \'string\', \'en\': \'string-en\'}`')
            ->setCode(TranslationValue::INVALID_FORMAT_ERROR)
            ->setInvalidValue($translationValueDto)
            ->assertRaised();
    }

    /**
     * @param array<string, string> $values
     *
     * @dataProvider getUnsupportedLocaleValues
     */
    public function testUnsupportedLocaleValues(array $values): void
    {
        $translationValueDto = new TranslationValueDto($values);
        $constraint = new TranslationValue(supportedLocales: ['en']);

        $this->validator->validate($translationValueDto, $constraint);

        $this->buildViolation('The locale is unsupported. It should be one of: {{ locales }}')
            ->setCode(TranslationValue::UNSUPPORTED_LOCALE_ERROR)
            ->setParameter('{{ locales }}', 'en')
            ->assertRaised();
    }

    /**
     * @param array<string, string> $values
     *
     * @dataProvider getValidValuesMaxLength
     */
    public function testValidValuesMaxLength(array $values): void
    {
        $translationValueDto = new TranslationValueDto($values);
        $this->validator->validate($translationValueDto, new TranslationValue(maxLength: 5));

        $this->assertNoViolation();
    }

    /**
     * @param array<string, string> $values
     *
     * @dataProvider getInvalidValuesMaxLength
     */
    public function testInvalidValuesMaxLength(array $values): void
    {
        $translationValueDto = new TranslationValueDto($values);
        $constraint = new TranslationValue(maxLength: 5);

        $this->validator->validate($translationValueDto, $constraint);

        $this->buildViolation('This value is too long. It should have {{ limit }} character or less.|This value is too long. It should have {{ limit }} characters or less.')
            ->setCode(TranslationValue::TOO_LONG_ERROR)
            ->setParameter('{{ limit }}', '5')
            ->setPlural(5)
            ->assertRaised();
    }

    public static function getValidValues(): \Iterator
    {
        yield 'translation with 1 locale' => [
            [
                'de' => 'string',
            ],
        ];
        yield 'translation with 2 locales' => [
            [
                'de' => 'string',
                'en' => 'string-en',
            ],
        ];
    }

    public static function getInvalidFormatValues(): \Iterator
    {
        yield 'empty translation' => [
            [],
        ];
        yield 'translation with 1 locale and invalid format' => [
            [
                'de',
            ],
        ];
        yield 'translation with 2 locales and invalid format' => [
            [
                'de',
                'en',
            ],
        ];
        yield 'translation with 1 locale in invalid format' => [
            [
                4711 => 'string',
            ],
        ];
    }

    public static function getUnsupportedLocaleValues(): \Iterator
    {
        yield 'translation with 1 unsupported locale' => [
            [
                'foo' => 'string',
                'en' => 'string-en',
            ],
        ];
    }

    public static function getValidValuesMaxLength(): \Iterator
    {
        yield 'translation with 1 locale and 1 character' => [
            [
                'de' => 'a',
            ],
        ];
        yield 'translation with 2 locales and 3 characters each' => [
            [
                'de' => 'foo',
                'en' => 'bar',
            ],
        ];
        yield 'translation with 2 locales and different character counts each' => [
            [
                'de' => 'foo',
                'en' => 'fooba',
            ],
        ];
    }

    public static function getInvalidValuesMaxLength(): \Iterator
    {
        yield 'translation with 1 locale and 6 characters' => [
            [
                'de' => 'foobar',
            ],
        ];
        yield 'translation with 2 locales and 7 characters' => [
            [
                'de' => 'foo_bar',
                'en' => 'bar_baz',
            ],
        ];
        yield 'translation with 2 locales and different character counts each' => [
            [
                'de' => 'foo',
                'en' => 'bar_baz',
            ],
        ];
    }
}
