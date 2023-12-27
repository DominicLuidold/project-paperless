<?php

declare(strict_types=1);

namespace App\StudyProgramme\Application\Service;

use App\Common\Domain\Exception\NonUniquePropertyException;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgramme;
use App\StudyProgramme\Domain\Repository\StudyProgrammeRepositoryInterface;
use App\StudyProgramme\Domain\Service\UniqueStudyProgrammeCodeCheckerInterface;

final readonly class UniqueStudyProgrammeCodeChecker implements UniqueStudyProgrammeCodeCheckerInterface
{
    public function __construct(
        private StudyProgrammeRepositoryInterface $studyProgrammeRepository,
    ) {
    }

    public function checkUniqueStudyProgrammeCode(string $code): void
    {
        $studyProgramme = $this->studyProgrammeRepository->findOneByCode($code);

        if (null !== $studyProgramme) {
            throw new NonUniquePropertyException(
                className: StudyProgramme::class,
                property: 'code',
                value: $studyProgramme->getCode()
            );
        }
    }
}
