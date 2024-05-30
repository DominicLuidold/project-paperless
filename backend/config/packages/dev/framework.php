<?php

declare(strict_types=1);

use Symfony\Config\Framework\ProfilerConfig;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $config): void {
    /** @var ProfilerConfig $profiler */
    $profiler = $config->profiler();
    $profiler
        ->onlyExceptions(false)
        ->collectSerializerData(true);
};
