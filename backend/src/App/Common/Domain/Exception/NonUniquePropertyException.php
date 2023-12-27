<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception;

use App\Common\Application\Utility\StringUtility;

final class NonUniquePropertyException extends InvalidInputDataException
{
    /**
     * @param class-string $className
     */
    public function __construct(string $className, string $property, string $value)
    {
        parent::__construct(
            message: sprintf(
                'An entity for class `%s` with `%s=%s` already exists.',
                StringUtility::getClassBasename($className),
                $property,
                $value
            ),
            code: 422
        );
    }
}
