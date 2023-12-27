<?php

declare(strict_types=1);

namespace Framework\Infrastructure\DataFixtures;

trait ReferenceTrait
{
    public static function getReferenceName(string|int $objectReference): string
    {
        return sprintf('%s_%s', self::class, $objectReference);
    }
}
