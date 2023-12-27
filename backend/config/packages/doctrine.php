<?php

declare(strict_types=1);

use Framework\Infrastructure\Types\TranslationValueObjectType;
use Symfony\Config\DoctrineConfig;

return static function (DoctrineConfig $doctrine): void {
    $doctrine->dbal([
        'connections' => [
            'default' => [
                'url' => '%env(resolve:DATABASE_URL)%',
                'charset' => 'utf-8',
                'driver' => 'pro_pqsl',
            ],
        ],
        'types' => [
            TranslationValueObjectType::NAME => ['class' => TranslationValueObjectType::class],
        ],
    ]);

    $doctrine->dbal()->connection('default')
        ->profilingCollectBacktrace((bool) '%kernel.debug%')
        ->useSavepoints(true);

    $doctrine->orm()
        ->autoGenerateProxyClasses(true)
        ->enableLazyGhostObjects(true)
        ->defaultEntityManager('default');

    $em = $doctrine->orm()->entityManager('default')
        ->reportFieldsWhereDeclared(true)
        ->validateXmlMapping(true)
        ->autoMapping(true)
        ->namingStrategy('doctrine.orm.naming_strategy.underscore_number_aware');

    $em->resultCacheDriver()->type(null);
    $em->metadataCacheDriver()->type('pool')->pool('cache.system');
    $em->queryCacheDriver()->type('pool')->pool('cache.system');
};
