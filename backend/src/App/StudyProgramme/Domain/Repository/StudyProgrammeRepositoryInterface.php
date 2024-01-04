<?php

declare(strict_types=1);

namespace App\StudyProgramme\Domain\Repository;

use App\Common\Domain\Id\StudyProgrammeId;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgramme;
use Framework\Infrastructure\Repository\QueryOptions;

interface StudyProgrammeRepositoryInterface
{
    public function findOneById(StudyProgrammeId $id): ?StudyProgramme;

    public function findOneByCode(string $code): ?StudyProgramme;

    /**
     * @return StudyProgramme[]
     */
    public function findAllByFilter(StudyProgrammeRepositoryFilter $filter, QueryOptions $options): array;

    public function countWithFilter(StudyProgrammeRepositoryFilter $filter): int;

    public function saveEntity(StudyProgramme $entity): void;
}
