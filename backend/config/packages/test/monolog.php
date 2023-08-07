<?php

declare(strict_types=1);

use Symfony\Config\MonologConfig;

return static function (MonologConfig $monolog): void {
    $main = $monolog->handler('main');

    $main->type('fingers_crossed')
        ->actionLevel('error')
        ->handler('nested')
        ->channels()->elements(['!event']);

    $main->excludedHttpCode()->code(404);
    $main->excludedHttpCode()->code(405);

    $monolog->handler('nested')
        ->type('stream')
        ->path('%kernel.logs_dir%/%kernel.environment%.log')
        ->level('debug');
};
