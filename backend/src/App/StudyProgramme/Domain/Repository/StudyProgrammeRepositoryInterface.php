<?php

declare(strict_types=1);

namespace App\StudyProgramme\Domain\Repository;

use App\Common\Domain\Id\StudyProgrammeId;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgramme;

interface StudyProgrammeRepositoryInterface
{
    public function findOneById(StudyProgrammeId $id): ?StudyProgramme;

    public function findOneByCode(string $code): ?StudyProgramme;
}
