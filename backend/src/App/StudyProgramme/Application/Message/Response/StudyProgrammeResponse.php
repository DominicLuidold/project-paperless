<?php

declare(strict_types=1);

namespace App\StudyProgramme\Application\Message\Response;

use App\Common\Domain\Id\StudyProgrammeId;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgramme;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgrammeType;
use Framework\Domain\Model\TranslationValueObject;

final readonly class StudyProgrammeResponse
{
    private function __construct(
        public StudyProgrammeId $id,
        public TranslationValueObject $name,
        public StudyProgrammeType $type,
        public int $numberOfSemesters,
        public string $code,
    ) {
    }

    public static function fromEntity(StudyProgramme $studyProgramme): self
    {
        return new self(
            id: $studyProgramme->getId(),
            name: $studyProgramme->getName(),
            type: $studyProgramme->getType(),
            numberOfSemesters: $studyProgramme->getNumberOfSemesters(),
            code: $studyProgramme->getCode(),
        );
    }
}
