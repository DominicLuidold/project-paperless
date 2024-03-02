<?php

declare(strict_types=1);

namespace App\StudyProgramme\Application\Message\CommandHandler;

use App\StudyProgramme\Application\Message\Command\CreateStudyProgrammeCommand;
use App\StudyProgramme\Application\Message\Response\StudyProgrammeResponse;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgramme;
use App\StudyProgramme\Domain\Repository\StudyProgrammeRepositoryInterface;
use App\StudyProgramme\Domain\Service\UniqueStudyProgrammeCodeCheckerInterface;
use Framework\Application\Message\CommandHandlerInterface;
use Framework\Domain\Model\TranslationValueObject;

final readonly class CreateStudyProgrammeCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private StudyProgrammeRepositoryInterface $studyProgrammeRepository,
        private UniqueStudyProgrammeCodeCheckerInterface $uniqueStudyProgrammeCodeChecker,
    ) {
    }

    public function __invoke(CreateStudyProgrammeCommand $command): StudyProgrammeResponse
    {
        $studyProgramme = StudyProgramme::create(
            name: TranslationValueObject::create($command->name->values),
            type: $command->type,
            numberOfSemesters: $command->numberOfSemesters,
            code: $command->code,
            uniqueStudyProgrammeCodeChecker: $this->uniqueStudyProgrammeCodeChecker
        );
        $this->studyProgrammeRepository->saveEntity($studyProgramme);

        return StudyProgrammeResponse::fromEntity($studyProgramme);
    }
}
