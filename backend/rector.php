<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitThisCallRector;
use Rector\PHPUnit\Set\PHPUnitLevelSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonyLevelSetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/config',
        __DIR__.'/public',
        __DIR__.'/src',
        __DIR__.'/tests',
    ]);

    $rectorConfig->phpstanConfigs([
        __DIR__.'/phpstan.dist.neon',
        // Rector does not load PHPStan extensions automatically
        __DIR__.'/vendor/phpstan/phpstan-doctrine/extension.neon',
        __DIR__.'/vendor/phpstan/phpstan-phpunit/extension.neon',
        __DIR__.'/vendor/phpstan/phpstan-symfony/extension.neon',
        __DIR__.'/vendor/tomasvotruba/type-coverage/config/extension.neon',
    ]);

    $rectorConfig->sets([
        // PHP
        LevelSetList::UP_TO_PHP_83,
        SetList::CODE_QUALITY,
        SetList::PRIVATIZATION,
        SetList::TYPE_DECLARATION,

        // Symfony
        SymfonyLevelSetList::UP_TO_SYMFONY_63,
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        SymfonySetList::SYMFONY_CODE_QUALITY,

        // PHPUnit
        PHPUnitLevelSetList::UP_TO_PHPUNIT_100,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
    ]);

    $rectorConfig->skip([
        PreferPHPUnitThisCallRector::class,
    ]);
};
