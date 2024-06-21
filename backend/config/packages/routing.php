<?php

declare(strict_types=1);

use Symfony\Config\Framework\RouterConfig;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $config): void {
    /** @var RouterConfig $router */
    $router = $config->router();
    $router
        ->utf8(true)
        ->strictRequirements(true);
};
