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

    public function findOneById(StudyProgrammeId $id): ?StudyProgramme
    {
        return $this->createQueryBuilder('studyProgramme')
            ->andWhere('studyProgramme.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByCode(string $code): ?StudyProgramme
    {
        return $this->createQueryBuilder('studyProgramme')
            ->andWhere('studyProgramme.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllByFilter(StudyProgrammeRepositoryFilter $filter, QueryOptions $options): array
    {
        $qb = $this->createQueryBuilder('studyProgramme')
            ->orderBy('studyProgramme.'.$options->sort, $options->order)
            ->setFirstResult($options->offset)
            ->setMaxResults($options->limit);

        $this->handleFilters($qb, $filter);
        $paginator = new Paginator($qb);

        return iterator_to_array($paginator->getIterator());
    }

    public function countWithFilter(StudyProgrammeRepositoryFilter $filter): int
    {
        $qb = $this->createQueryBuilder('studyProgramme')
            ->select('COUNT(studyProgramme.id)');

        $this->handleFilters($qb, $filter);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    private function handleFilters(QueryBuilder $qb, StudyProgrammeRepositoryFilter $filter): void
    {
        if (null !== $type = $filter->type) {
            $qb->andWhere('studyProgramme.type = :type')
                ->setParameter('type', $type);
        }
    }
}
