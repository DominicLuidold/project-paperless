<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes->import('../src/App/*/Port/Http/**', 'attribute')
        ->namePrefix('api_')
        ->prefix('/api');
};
