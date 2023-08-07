<?php

declare(strict_types=1);

use Symfony\Config\MonologConfig;

return static function (MonologConfig $monolog): void {
    $monolog->handler('main')
        ->type('stream')
        ->path('%kernel.logs_dir%/%kernel.environment%.log')
        ->level('debug')
        ->channels()->elements(['!event', '!deprecation']);

    $monolog->handler('console')
        ->type('console')
        ->processPsr3Messages(false)
        ->channels()->elements(['!event', '!doctrine', '!console']);

    $monolog->handler('deprecation')
        ->type('stream')
        ->path('%kernel.logs_dir%/%kernel.environment%.deprecations.log')
        ->channels()->elements(['deprecation']);
};
