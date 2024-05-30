<?php

declare(strict_types=1);

namespace App\Tests\Test;

use Symfony\Component\HttpKernel\Profiler\Profile;

class WebDatabaseTestCase extends WebTestCase
{
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        static::$client->enableProfiler();
    }

    protected static function assertQueryCount(int $expected, string $message = ''): void
    {
        if (!static::$client->getProfile() instanceof Profile) {
            self::fail(
                sprintf(
                    'Failed asserting that %d database queries were executed: Profiling is disabled or configured incorrectly',
                    $expected
                )
            );
        }

        // @phpstan-ignore method.notFound
        $queryCount = static::$client->getProfile()->getCollector('db')->getQueryCount();

        self::assertLessThanOrEqual($expected, $queryCount, $message);
    }
}
