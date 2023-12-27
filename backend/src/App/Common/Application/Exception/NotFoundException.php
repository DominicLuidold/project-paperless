<?php

declare(strict_types=1);

namespace App\Common\Application\Exception;

use App\Common\Application\Utility\StringUtility;
use Framework\Application\Exception\ApplicationExceptionInterface;

final class NotFoundException extends \RuntimeException implements ApplicationExceptionInterface
{
    public const DEFAULT_PROPERTY_NAME = 'id';

    private function __construct(string $message)
    {
        parent::__construct(message: $message, code: 404);
    }

    /**
     * @param class-string $className
     */
    public static function default(
        string $className,
        mixed $propertyValue,
        string $propertyName = self::DEFAULT_PROPERTY_NAME
    ): self {
        return new self(
            sprintf(
                '%s with `%s=%s` not found',
                StringUtility::getClassBasename($className),
                $propertyName,
                $propertyValue
            )
        );
    }

    /**
     * @param class-string $childClassName
     * @param class-string $parentClassName
     */
    public static function dependent(
        string $childClassName,
        string $parentClassName,
        mixed $childPropertyValue,
        mixed $parentPropertyValue,
        string $childPropertyName = self::DEFAULT_PROPERTY_NAME,
        string $parentPropertyName = self::DEFAULT_PROPERTY_NAME,
    ): self {
        return new self(
            sprintf(
                '%s with `%s=%s` for %s with `%s=%s` not found',
                StringUtility::getClassBasename($childClassName),
                $childPropertyName,
                $childPropertyValue,
                StringUtility::getClassBasename($parentClassName),
                $parentPropertyName,
                $parentPropertyValue,
            )
        );
    }
}
