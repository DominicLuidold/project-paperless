<?php

declare(strict_types=1);

namespace App\Tests\Functional\App;

use App\Tests\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class NelmioApiDocsTest extends WebTestCase
{
    public function testApiDoc(): void
    {
        self::$client->request(Request::METHOD_GET, '/api/doc');
        self::assertResponseStatusCodeSame(200);
    }
}
