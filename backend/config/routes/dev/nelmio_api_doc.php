<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('app.swagger', '/api/docs.json')
        ->methods(['GET'])
        ->controller('nelmio_api_doc.controller.swagger');

    $routes->add('app.swagger_ui', '/api/docs')
        ->methods(['GET'])
        ->controller('nelmio_api_doc.controller.swagger_ui');
};
