<?php

declare(strict_types=1);

use Symfony\Config\NelmioApiDocConfig;

return static function (NelmioApiDocConfig $nelmioApiDoc): void {
    $nelmioApiDoc
        ->documentation('info', [
            'title' => 'Project Paperless',
            'description' => 'API documentation for Project Paperless',
        ]);

    $nelmioApiDoc->areas('default', ['path_patterns' => ['^/api(?!/docs)']]);
};
