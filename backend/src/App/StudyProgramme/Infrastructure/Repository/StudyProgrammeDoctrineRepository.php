<?php

declare(strict_types=1);

namespace App\StudyProgramme\Infrastructure\Repository;

use App\Common\Domain\Id\StudyProgrammeId;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgramme;
use App\StudyProgramme\Domain\Repository\StudyProgrammeRepositoryFilter;
use App\StudyProgramme\Domain\Repository\StudyProgrammeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Framework\Infrastructure\Repository\QueryOptions;
use Framework\Infrastructure\Repository\RepositoryTrait;

/**
 * @extends ServiceEntityRepository<StudyProgramme>
 */
final class StudyProgrammeDoctrineRepository extends ServiceEntityRepository implements StudyProgrammeRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudyProgramme::class);

        $this->entityManager = $this->getEntityManager();
    }

    public function findOneByCode(string $code): ?StudyProgramme
    {
        return $this->createQueryBuilder('studyProgramme')
            ->andWhere('studyProgramme.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();
    }
}