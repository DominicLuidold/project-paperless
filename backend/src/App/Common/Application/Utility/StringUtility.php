<?php

declare(strict_types=1);

namespace App\Common\Application\Utility;

final readonly class StringUtility
{
    /**
     * @param class-string $className
     */
    public static function getClassBasename(string $className): string
    {
        /** @var string $basename */
        $basename = strrchr($className, '\\');

        return substr($basename, 1);
    }
}
