<?php

declare(strict_types=1);

use App\Common\Infrastructure\Types\StudyProgrammeIdType;
use Framework\Infrastructure\Types\TranslationValueObjectType;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig\MappingConfig;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig\MetadataCacheDriverConfig;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig\QueryCacheDriverConfig;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig\ResultCacheDriverConfig;
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
        ->profilingCollectBacktrace((bool) '%kernel.debug%');

    $doctrine->orm()
        ->autoGenerateProxyClasses(true)
        ->enableLazyGhostObjects(true)
        ->defaultEntityManager('default')
        ->controllerResolver()
            ->autoMapping(false);

    $em = $doctrine->orm()->entityManager('default')
        ->reportFieldsWhereDeclared(true)
        ->validateXmlMapping(true)
        ->autoMapping(true)
        ->namingStrategy('doctrine.orm.naming_strategy.underscore_number_aware');

    foreach (['StudyProgramme'] as $entity) {
        /** @var MappingConfig $mapping */
        $mapping = $em->mapping($entity);
        $mapping
            ->isBundle(false)
            ->type('xml')
            ->dir('%kernel.project_dir%/src/App/'.$entity.'/Infrastructure/Resources/config')
            ->prefix('App\\'.$entity.'\Domain\Model')
            ->alias($entity);
    }

    /** @var ResultCacheDriverConfig $resultCacheDriver */
    $resultCacheDriver = $em->resultCacheDriver();
    $resultCacheDriver->type(null);
    /** @var MetadataCacheDriverConfig $metadataCacheDriver */
    $metadataCacheDriver = $em->metadataCacheDriver();
    $metadataCacheDriver->type('pool')->pool('cache.system');
    /** @var QueryCacheDriverConfig $queryCacheDriver */
    $queryCacheDriver = $em->queryCacheDriver();
    $queryCacheDriver->type('pool')->pool('cache.system');
};
