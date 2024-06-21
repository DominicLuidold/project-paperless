<?php

declare(strict_types=1);

use Symfony\Config\Framework\Messenger\TransportConfig;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework): void {
    $messenger = $framework->messenger();

    $messenger->defaultBus('event.bus')
        ->failureTransport('failed');

    /** @var TransportConfig $syncTransport */
    $syncTransport = $messenger->transport('sync');
    $syncTransport->dsn('sync://');

    /** @var TransportConfig $asyncTransport */
    $asyncTransport = $messenger->transport('async');
    $asyncTransport
        ->dsn('%env(MESSENGER_TRANSPORT_DSN)%')
        // @phpstan-ignore argument.type (based on Symfony documentation examples)
        ->options(['queue_name' => 'async', 'auto_setup' => false])
        ->retryStrategy()
            ->maxRetries(3)
            ->delay(1000)
            ->maxDelay(0)
            ->multiplier(10);

    /** @var TransportConfig $failedTransport */
    $failedTransport = $messenger->transport('failed');
    $failedTransport
        ->dsn('%env(MESSENGER_TRANSPORT_DSN)%')
        // @phpstan-ignore argument.type (based on Symfony documentation examples)
        ->options(['queue_name' => 'failed', 'auto_setup' => false]);

    $commandBus = $messenger->bus('command.bus');
    $commandBus->middleware()->id('dispatch_after_current_bus');
    $commandBus->middleware()->id('doctrine_transaction');

    $queryBus = $messenger->bus('query.bus');
    $queryBus->middleware()->id('dispatch_after_current_bus');

    $eventBus = $messenger->bus('event.bus');
    $eventBus->middleware()->id('dispatch_after_current_bus');
    $eventBus->middleware()->id('doctrine_transaction');
};
