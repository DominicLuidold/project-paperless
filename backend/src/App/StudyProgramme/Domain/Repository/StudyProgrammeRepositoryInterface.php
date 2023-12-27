<?php

declare(strict_types=1);

namespace App\StudyProgramme\Domain\Repository;

use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgramme;

interface StudyProgrammeRepositoryInterface
{
    public function findOneByCode(string $code): ?StudyProgramme;
}
