<?php

declare(strict_types=1);

namespace Framework\Infrastructure\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Intended as a base class for all test fixtures used in tests.
 */
abstract class TestFixture extends Fixture implements FixtureGroupInterface
{
    /**
     * @return array<int|string, mixed>
     */
    abstract public static function getData(): array;

    public static function getGroups(): array
    {
        return [
            'test',
            static::class,
        ];
    }
}
