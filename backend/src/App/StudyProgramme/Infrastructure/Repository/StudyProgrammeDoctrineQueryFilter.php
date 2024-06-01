<?php

declare(strict_types=1);

namespace App\StudyProgramme\Infrastructure\Repository;

use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgrammeType;
use Doctrine\ORM\QueryBuilder;
use Framework\Infrastructure\Repository\Doctrine\DoctrineQueryFilterInterface;
use Framework\Infrastructure\Repository\Doctrine\SortingTrait;

final class StudyProgrammeDoctrineQueryFilter implements DoctrineQueryFilterInterface
{
    use SortingTrait;

    private ?StudyProgrammeType $type = null;

    #[\Override]
    public function updateQueryBuilder(QueryBuilder $qb): QueryBuilder
    {
        if (null !== $this->type) {
            $qb->andWhere('studyProgramme.type = :type')
                ->setParameter('type', $this->type);
        }

        if (null !== $this->sort) {
            $qb->orderBy($this->getMappedSort($this->sort) ?? 'studyProgramme.id', $this->order);
        }

        return $qb;
    }

    public function setType(?StudyProgrammeType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    #[\Override]
    public static function getSortableFieldsMapping(): array
    {
        return [
            'id' => 'studyProgramme.id',
            'type' => 'studyProgramme.type',
            'numberOfSemesters' => 'studyProgramme.numberOfSemesters',
        ];
    }
}
