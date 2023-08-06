<?php

declare(strict_types=1);

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $config): void {
    $cache = $config->cache();

    $cache->app('cache.adapter.redis')
        ->defaultRedisProvider('%env(REDIS_DNS)%');
};
