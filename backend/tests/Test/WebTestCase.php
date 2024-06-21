<?php

declare(strict_types=1);

namespace App\Tests\Test;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SymfonyWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class WebTestCase extends SymfonyWebTestCase
{
    protected static KernelBrowser $client;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $config = [
            'environment' => 'test',
            'debug' => true,
            'host' => 'localhost',
            'HTTP_ACCEPT' => 'application/json',
        ];

        self::$client = static::createClient($config);
    }

    /**
     * @param array<mixed> $data
     */
    protected function makeJsonRequest(string $method, string $uri, array $data = []): Response
    {
        try {
            $content = json_encode(value: $data, flags: \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            self::fail(sprintf('Could not encode JSON request data: %s', $e->getMessage()));
        }

        self::$client->request(
            method: $method,
            uri: $uri,
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $content,
        );

        return self::$client->getResponse();
    }

    /**
     * @param array<string, mixed> $response
     */
    protected function validatePaginatedResponse(array $response): void
    {
        self::assertArrayHasKey('page', $response);
        self::assertArrayHasKey('limit', $response);
        self::assertArrayHasKey('pages', $response);
        self::assertArrayHasKey('total', $response);
        self::assertArrayHasKey('_embedded', $response);

        if ($response['limit'] >= $response['total']) {
            self::assertCount($response['total'], $response['_embedded']);
        } else {
            self::assertLessThanOrEqual($response['limit'], \count($response['_embedded']));
        }
    }

    /**
     * @return array<mixed>
     */
    protected static function getJsonResponse(): array
    {
        $content = self::$client->getResponse()->getContent();
        $content = false === $content ? '' : $content;

        try {
            return (array) json_decode(json: $content, associative: true, flags: \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            self::fail(sprintf('Could not decode JSON response: %s', $e->getMessage()));
        }
    }
}
