<?php

declare(strict_types=1);

namespace App\StudyProgramme\Application\Message\Query;

use App\Common\Domain\Id\StudyProgrammeId;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetStudyProgrammeQuery
{
    public function __construct(
        #[Assert\NotNull]
        public StudyProgrammeId $id,
    ) {
    }
}
