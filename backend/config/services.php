<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Framework\Application\Messenger\CommandHandlerInterface;
use Framework\Application\Messenger\EventHandlerInterface;
use Framework\Application\Messenger\QueryHandlerInterface;
use Fusonic\DDDExtensions\Doctrine\EventSubscriber\DomainEventSubscriber;
use Fusonic\HttpKernelExtensions\Controller\RequestDtoResolver;
use Fusonic\HttpKernelExtensions\Normalizer\ConstraintViolationExceptionNormalizer;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->instanceof(CommandHandlerInterface::class)
        ->tag('messenger.message_handler', ['bus' => 'command.bus']);

    $services->instanceof(QueryHandlerInterface::class)
        ->tag('messenger.message_handler', ['bus' => 'query.bus']);

    $services->instanceof(EventHandlerInterface::class)
        ->tag('messenger.message_handler', ['bus' => 'event.bus']);

    $services->load('App\\', '../src/App/*');
    $services->load('Framework\\', '../src/Framework/*');

    $services->set(RequestDtoResolver::class)
        ->tag('controller.argument_value_resolver', [
            'priority' => 50,
        ])
        ->arg('$providers', tagged_iterator('fusonic.http_kernel_extensions.context_aware_provider'));

    $services->set(ConstraintViolationExceptionNormalizer::class)
        ->arg('$normalizer', service('serializer.normalizer.constraint_violation_list'));

    $services->set(DomainEventSubscriber::class)
        ->arg('$bus', service('event.bus'))
        ->tag('doctrine.event_subscriber');
};
