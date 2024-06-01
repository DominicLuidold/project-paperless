<?php

declare(strict_types=1);

namespace App\StudyProgramme\Application\Message\CommandHandler;

use App\Common\Application\Exception\NotFoundException;
use App\StudyProgramme\Application\Message\Command\UpdateStudyProgrammeCommand;
use App\StudyProgramme\Application\Message\Response\StudyProgrammeResponse;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgramme;
use App\StudyProgramme\Domain\Repository\StudyProgrammeRepositoryInterface;
use App\StudyProgramme\Domain\Service\UniqueStudyProgrammeCodeCheckerInterface;
use Framework\Application\Message\CommandHandlerInterface;
use Framework\Domain\Model\TranslationValueObject;

final readonly class UpdateStudyProgrammeCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private StudyProgrammeRepositoryInterface $studyProgrammeRepository,
        private UniqueStudyProgrammeCodeCheckerInterface $uniqueStudyProgrammeCodeChecker,
    ) {
    }

    public function __invoke(UpdateStudyProgrammeCommand $command): StudyProgrammeResponse
    {
        $studyProgramme = $this->studyProgrammeRepository->findOneById($command->id);
        if (null === $studyProgramme) {
            throw NotFoundException::default(StudyProgramme::class, $command->id);
        }

        $studyProgramme->setName(TranslationValueObject::create($command->name->values));
        $studyProgramme->setType($command->type);
        $studyProgramme->setNumberOfSemesters($command->numberOfSemesters);
        $studyProgramme->updateCode($command->code, $this->uniqueStudyProgrammeCodeChecker);

        return StudyProgrammeResponse::fromEntity($studyProgramme);
    }
}
