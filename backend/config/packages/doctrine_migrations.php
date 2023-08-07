<?php

declare(strict_types=1);

use Symfony\Config\DoctrineMigrationsConfig;

return static function (DoctrineMigrationsConfig $migrations): void {
    $migrations
        ->migrationsPath('Application\Migrations', '%kernel.project_dir%/migrations')
        ->organizeMigrations('BY_YEAR_AND_MONTH')
        ->storage()->tableStorage()->tableName('migration_versions');
};
