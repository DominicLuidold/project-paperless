<?php

declare(strict_types=1);

use Fusonic\HttpKernelExtensions\Attribute\FromRequest;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('fusonic_api_documentation', [
        'request_object_class' => FromRequest::class,
    ]);
};
