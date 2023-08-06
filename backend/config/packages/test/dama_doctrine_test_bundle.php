<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('dama_doctrine_test', [
        'enable_static_connection' => true,
        'enable_static_meta_data_cache' => true,
        'enable_static_query_cache' => true,
    ]);
};
