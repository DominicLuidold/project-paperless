<?php

declare(strict_types=1);

use App\Common\Infrastructure\Types\StudyProgrammeIdType;
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
            StudyProgrammeIdType::NAME => ['class' => StudyProgrammeIdType::class],
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

    foreach (['StudyProgramme'] as $entity) {
        $em->mapping($entity)
            ->isBundle(false)
            ->type('xml')
            ->dir('%kernel.project_dir%/src/App/'.$entity.'/Infrastructure/Resources/config')
            ->prefix('App\\'.$entity.'\Domain\Model')
            ->alias($entity);
    }

    $em->resultCacheDriver()->type(null);
    $em->metadataCacheDriver()->type('pool')->pool('cache.system');
    $em->queryCacheDriver()->type('pool')->pool('cache.system');
};
