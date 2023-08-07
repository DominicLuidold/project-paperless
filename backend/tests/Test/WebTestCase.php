<?php

declare(strict_types=1);

namespace App\Tests\Test;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SymfonyWebTestCase;

class WebTestCase extends SymfonyWebTestCase
{
    protected static KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $config = [
            'environment' => 'test',
            'debug' => true,
            'host' => 'localhost',
            'HTTP_ACCEPT' => 'application/json',
        ];

        self::$client = parent::createClient($config);
    }
}
