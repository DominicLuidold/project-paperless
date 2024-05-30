<?php

declare(strict_types=1);

use Symfony\Config\Framework\SessionConfig;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $config): void {
    $config
        ->secret('%env(APP_SECRET)%')
        ->httpMethodOverride(false)
        ->handleAllThrowables(true)
        ->phpErrors()->log();

    /** @var SessionConfig $session */
    $session = $config->session();
    $session
        ->handlerId(null)
        ->cookieSecure('auto')
        ->cookieSamesite('lax');
};
