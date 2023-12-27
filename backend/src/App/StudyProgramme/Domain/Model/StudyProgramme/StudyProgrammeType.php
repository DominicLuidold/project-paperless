<?php

declare(strict_types=1);

namespace App\StudyProgramme\Domain\Model\StudyProgramme;

enum StudyProgrammeType: string
{
    case BACHELOR = 'BACHELOR';
    case MASTER = 'MASTER';
}
