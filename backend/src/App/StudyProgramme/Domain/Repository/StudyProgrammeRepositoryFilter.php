<?php

declare(strict_types=1);

namespace App\StudyProgramme\Domain\Repository;

use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgrammeType;

final readonly class StudyProgrammeRepositoryFilter
{
    public function __construct(
        public ?StudyProgrammeType $type = null,
    ) {
    }
}
