<?php

declare(strict_types=1);

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $config): void {
    $config
        ->secret('%env(APP_SECRET)%')
        ->httpMethodOverride(false)
        ->handleAllThrowables(true)
        ->phpErrors()->log();

    $config->session()
        ->handlerId(null)
        ->cookieSecure('auto')
        ->cookieSamesite('lax')
        ->storageFactoryId('session.storage.factory.native');
};
