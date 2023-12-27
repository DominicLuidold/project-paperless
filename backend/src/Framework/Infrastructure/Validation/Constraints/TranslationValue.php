<?php

declare(strict_types=1);

namespace Framework\Infrastructure\Validation\Constraints;

use Framework\Domain\Model\TranslationValueObject;
use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class TranslationValue extends Constraint
{
    public const INVALID_FORMAT_ERROR = 'bf172ce5-300d-41ea-85c9-d027f87c0b00';
    public const UNSUPPORTED_LOCALE_ERROR = '9a0969e2-1c79-4c47-bb1d-a1e9a97a3d1b';
    public const TOO_LONG_ERROR = '438235ce-c740-4f1b-a7c8-fbf71572e6e0';

    protected const ERROR_NAMES = [
        self::INVALID_FORMAT_ERROR => 'INVALID_FORMAT_ERROR',
        self::UNSUPPORTED_LOCALE_ERROR => 'UNSUPPORTED_LOCALE_ERROR',
        self::TOO_LONG_ERROR => 'TOO_LONG_ERROR',
    ];

    /**
     * @param string[] $supportedLocales
     */
    public function __construct(
        public array $supportedLocales = TranslationValueObject::SUPPORTED_LOCALES,
        public ?int $maxLength = null,
        public string $invalidFormatMessage = 'Invalid translation format. Correct example: `{\'de\': \'string\', \'en\': \'string-en\'}`',
        public string $unsupportedLocaleMessage = 'The locale is unsupported. It should be one of: {{ locales }}',
        public string $maxLengthMessage = 'This value is too long. It should have {{ limit }} character or less.|This value is too long. It should have {{ limit }} characters or less.',
        array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct([], $groups, $payload);
    }
}
