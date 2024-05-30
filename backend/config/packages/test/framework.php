<?php

declare(strict_types=1);

use Symfony\Config\Framework\ProfilerConfig;
use Symfony\Config\Framework\SessionConfig;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $config): void {
    $config->test(true);

    /** @var SessionConfig $session */
    $session = $config->session();
    $session->storageFactoryId('session.storage.factory.mock_file');

    /** @var ProfilerConfig $profiler */
    $profiler = $config->profiler();
    $profiler->collect(false);
};
