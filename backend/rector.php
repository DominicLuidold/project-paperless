<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\Config\RectorConfig;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitThisCallRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
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
        // Rector does not load PHPStan extensions automatically when phpstan/extension-installer is used
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
        SymfonySetList::SYMFONY_64,
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        SymfonySetList::SYMFONY_CODE_QUALITY,

        // PHPUnit
        PHPUnitSetList::PHPUNIT_100,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
    ]);

    $rectorConfig->skip([
        FinalizeClassesWithoutChildrenRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
        PreferPHPUnitThisCallRector::class,
    ]);
};
