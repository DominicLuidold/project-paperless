<?php

declare(strict_types=1);

namespace Framework\Infrastructure\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Faker;

/**
 * Intended as a base class for all development fixtures. Development fixtures are not the same as test fixtures,
 * since they have a random factor.
 */
abstract class DevelopmentFixture extends Fixture implements FixtureGroupInterface
{
    protected Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = Faker\Factory::create();
    }

    /**
     * @return string[]
     */
    #[\Override]
    public static function getGroups(): array
    {
        return [
            'development',
            static::class,
        ];
    }
}
