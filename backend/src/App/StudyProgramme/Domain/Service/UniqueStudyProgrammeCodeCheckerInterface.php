<?php

declare(strict_types=1);

namespace App\StudyProgramme\Domain\Service;

interface UniqueStudyProgrammeCodeCheckerInterface
{
    public function checkUniqueStudyProgrammeCode(string $code): void;
}
