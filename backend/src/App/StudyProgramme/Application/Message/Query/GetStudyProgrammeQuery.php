<?php

declare(strict_types=1);

namespace App\StudyProgramme\Application\Message\Query;

use App\Common\Domain\Id\StudyProgrammeId;

final readonly class GetStudyProgrammeQuery
{
    public function __construct(
        public StudyProgrammeId $id,
    ) {
    }
}
