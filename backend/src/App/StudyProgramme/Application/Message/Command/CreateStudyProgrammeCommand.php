<?php

declare(strict_types=1);

namespace App\StudyProgramme\Application\Message\Command;

use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgrammeType;
use Framework\Domain\Model\TranslationValueDto;
use Framework\Infrastructure\Validation\Constraints as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateStudyProgrammeCommand
{
    public function __construct(
        #[Assert\NotNull]
        #[CustomAssert\TranslationValue]
        public TranslationValueDto $name,

        #[Assert\NotNull]
        public StudyProgrammeType $type,

        #[Assert\NotNull]
        #[Assert\Range(min: 4, max: 9)]
        public int $numberOfSemesters,

        #[Assert\NotBlank]
        public string $code
    ) {
    }
}
