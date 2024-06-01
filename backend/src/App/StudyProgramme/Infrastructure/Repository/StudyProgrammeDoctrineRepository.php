<?php

declare(strict_types=1);

namespace App\StudyProgramme\Infrastructure\Repository;

use App\Common\Domain\Id\StudyProgrammeId;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgramme;
use App\StudyProgramme\Domain\Repository\StudyProgrammeRepositoryFilter;
use App\StudyProgramme\Domain\Repository\StudyProgrammeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Framework\Infrastructure\Repository\Doctrine\RepositoryTrait;
use Framework\Infrastructure\Repository\QueryOptions;

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

    #[\Override]
    public function findOneById(StudyProgrammeId $id): ?StudyProgramme
    {
        return $this->createQueryBuilder('studyProgramme')
            ->andWhere('studyProgramme.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    #[\Override]
    public function findOneByCode(string $code): ?StudyProgramme
    {
        return $this->createQueryBuilder('studyProgramme')
            ->andWhere('studyProgramme.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();
    }

    #[\Override]
    public function findByFilter(
        StudyProgrammeRepositoryFilter $filter = new StudyProgrammeRepositoryFilter(),
        QueryOptions $options = new QueryOptions()
    ): array {
        $qb = $this->createQueryBuilder('studyProgramme')
            ->setFirstResult($options->offset)
            ->setMaxResults($options->limit);

        $qb = (new StudyProgrammeDoctrineQueryFilter())
            ->setType($filter->type)
            ->setSort($options->sort)
            ->setOrder($options->order)
            ->updateQueryBuilder($qb);

        return iterator_to_array((new Paginator($qb))->getIterator());
    }

    #[\Override]
    public function countWithFilter(StudyProgrammeRepositoryFilter $filter): int
    {
        $qb = $this->createQueryBuilder('studyProgramme')
            ->select('COUNT(studyProgramme.id)');

        $qb = (new StudyProgrammeDoctrineQueryFilter())
            ->setType($filter->type)
            ->updateQueryBuilder($qb);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
