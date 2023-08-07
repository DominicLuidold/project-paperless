<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Test\WebTestCase;

final class NelmioApiDocsTest extends WebTestCase
{
    public function testApiDoc(): void
    {
        self::$client->request('GET', '/api/doc');
        self::assertResponseStatusCodeSame(200);
    }
}
