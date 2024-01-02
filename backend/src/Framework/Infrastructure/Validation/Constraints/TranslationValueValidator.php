<?php

declare(strict_types=1);

namespace Framework\Infrastructure\Validation\Constraints;

use Framework\Domain\Model\TranslationValueDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class TranslationValueValidator extends ConstraintValidator
{
    /**
     * @param mixed|TranslationValueDto $value
     */
    #[\Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!($constraint instanceof TranslationValue)) {
            throw new UnexpectedTypeException($constraint, TranslationValue::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof TranslationValueDto) {
            throw new UnexpectedValueException($value, TranslationValueDto::class);
        }

        // @phpstan-ignore-next-line
        $keys = array_filter(array_keys($value->values), static fn (mixed $key): bool => \is_string($key));

        if ([] === $keys || [] === array_values($value->values)) {
            $this->context->buildViolation($constraint->invalidFormatMessage)
                ->setCode(TranslationValue::INVALID_FORMAT_ERROR)
                ->setInvalidValue($value)
                ->addViolation();
        }

        foreach ($keys as $key) {
            if (!\in_array($key, $constraint->supportedLocales, true)) {
                $this->context->buildViolation($constraint->unsupportedLocaleMessage)
                    ->setCode(TranslationValue::UNSUPPORTED_LOCALE_ERROR)
                    ->setParameter('{{ locales }}', implode(', ', $constraint->supportedLocales))
                    ->addViolation();
            }
        }

        if (null !== $constraint->maxLength) {
            foreach ($value->values as $str) {
                if (mb_strlen($str) > $constraint->maxLength) {
                    $this->context->buildViolation($constraint->maxLengthMessage)
                        ->setCode(TranslationValue::TOO_LONG_ERROR)
                        ->setParameter('{{ limit }}', (string) $constraint->maxLength)
                        ->setPlural($constraint->maxLength)
                        ->addViolation();

                    break;
                }
            }
        }
    }
}
