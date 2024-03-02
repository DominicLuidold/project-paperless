<?php

declare(strict_types=1);

namespace App\Tests\Unit\App\StudyProgramme\Application\Message\CommandHandler;

use App\Common\Application\Exception\NotFoundException;
use App\Common\Domain\Exception\NonUniquePropertyException;
use App\Common\Domain\Id\StudyProgrammeId;
use App\StudyProgramme\Application\Message\Command\UpdateStudyProgrammeCommand;
use App\StudyProgramme\Application\Message\CommandHandler\UpdateStudyProgrammeCommandHandler;
use App\StudyProgramme\Application\Service\UniqueStudyProgrammeCodeChecker;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgrammeType;
use App\Tests\Unit\App\StudyProgramme\Domain\Model\StudyProgrammeBuilder;
use App\Tests\Unit\App\StudyProgramme\Infrastructure\Repository\FakeStudyProgrammeRepository;
use Framework\Domain\Model\TranslationValueDto;
use PHPUnit\Framework\TestCase;

final class UpdateStudyProgrammeCommandHandlerTest extends TestCase
{
    public function testUpdateStudyProgramme(): void
    {
        // arrange
        $studyProgrammeRepository = new FakeStudyProgrammeRepository();
        $uniqueStudyProgrammeCodeChecker = new UniqueStudyProgrammeCodeChecker($studyProgrammeRepository);

        $studyProgramme = StudyProgrammeBuilder::create($uniqueStudyProgrammeCodeChecker)->build();

        $studyProgrammeRepository->withFindByIdResults($studyProgramme);

        $handler = new UpdateStudyProgrammeCommandHandler($studyProgrammeRepository, $uniqueStudyProgrammeCodeChecker);
        $command = new UpdateStudyProgrammeCommand(
            id: $studyProgramme->getId(),
            name: new TranslationValueDto([
                'de' => 'Deutsch-Neu',
                'en' => 'English-New',
            ]),
            type: StudyProgrammeType::MASTER,
            numberOfSemesters: 4,
            code: '05678',
        );

        // act
        $response = $handler($command);

        // assert
        self::assertSame($command->name->values, $response->name->getValues());
        self::assertSame($command->type, $response->type);
        self::assertSame($command->numberOfSemesters, $response->numberOfSemesters);
        self::assertSame($command->code, $response->code);
    }

    public function testUpdateNotExistingStudyProgramme(): void
    {
        // assert
        $this->expectException(NotFoundException::class);

        // arrange
        $studyProgrammeRepository = new FakeStudyProgrammeRepository();
        $uniqueStudyProgrammeCodeChecker = new UniqueStudyProgrammeCodeChecker($studyProgrammeRepository);

        $handler = new UpdateStudyProgrammeCommandHandler($studyProgrammeRepository, $uniqueStudyProgrammeCodeChecker);
        $command = new UpdateStudyProgrammeCommand(
            id: new StudyProgrammeId(),
            name: new TranslationValueDto([
                'de' => 'Deutsch-Neu',
                'en' => 'English-New',
            ]),
            type: StudyProgrammeType::MASTER,
            numberOfSemesters: 4,
            code: '05678',
        );

        // act
        $handler($command);
    }

    public function testUpdateStudyProgrammeWithAlreadyExistingCode(): void
    {
        // assert
        $this->expectException(NonUniquePropertyException::class);

        // arrange
        $studyProgrammeRepository = new FakeStudyProgrammeRepository();
        $uniqueStudyProgrammeCodeChecker = new UniqueStudyProgrammeCodeChecker($studyProgrammeRepository);

        $studyProgramme = StudyProgrammeBuilder::create($uniqueStudyProgrammeCodeChecker)->build();
        $studyProgrammeWithConflictingCode = StudyProgrammeBuilder::create($uniqueStudyProgrammeCodeChecker)
            ->setCode('05678')
            ->build();

        $studyProgrammeRepository
            ->withFindByIdResults($studyProgramme)
            ->withFindByCodeResults($studyProgrammeWithConflictingCode);

        $handler = new UpdateStudyProgrammeCommandHandler($studyProgrammeRepository, $uniqueStudyProgrammeCodeChecker);
        $command = new UpdateStudyProgrammeCommand(
            id: $studyProgramme->getId(),
            name: new TranslationValueDto([
                'de' => 'Deutsch-Neu',
                'en' => 'English-New',
            ]),
            type: StudyProgrammeType::MASTER,
            numberOfSemesters: 4,
            code: $studyProgrammeWithConflictingCode->getCode(),
        );

        // act
        $handler($command);
    }
}
