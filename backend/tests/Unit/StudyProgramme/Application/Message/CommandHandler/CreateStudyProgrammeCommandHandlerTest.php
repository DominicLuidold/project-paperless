<?php

declare(strict_types=1);

namespace App\Tests\Unit\StudyProgramme\Application\Message\CommandHandler;

use App\Common\Domain\Exception\NonUniquePropertyException;
use App\StudyProgramme\Application\Message\Command\CreateStudyProgrammeCommand;
use App\StudyProgramme\Application\Message\CommandHandler\CreateStudyProgrammeCommandHandler;
use App\StudyProgramme\Application\Service\UniqueStudyProgrammeCodeChecker;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgrammeType;
use App\Tests\Unit\StudyProgramme\Domain\Model\StudyProgrammeBuilder;
use App\Tests\Unit\StudyProgramme\Infrastructure\Repository\FakeStudyProgrammeRepository;
use Framework\Domain\Model\TranslationValueDto;
use PHPUnit\Framework\TestCase;

final class CreateStudyProgrammeCommandHandlerTest extends TestCase
{
    public function testUpdateStudyProgramme(): void
    {
        // arrange
        $studyProgrammeRepository = new FakeStudyProgrammeRepository();
        $uniqueStudyProgrammeCodeChecker = new UniqueStudyProgrammeCodeChecker($studyProgrammeRepository);

        $handler = new CreateStudyProgrammeCommandHandler($studyProgrammeRepository, $uniqueStudyProgrammeCodeChecker);
        $command = new CreateStudyProgrammeCommand(
            name: new TranslationValueDto([
                'de' => 'Deutsch',
                'en' => 'English',
            ]),
            type: StudyProgrammeType::MASTER,
            numberOfSemesters: 4,
            code: '01234',
        );

        // act
        $response = $handler($command);

        // assert
        self::assertSame($command->name->values, $response->name->getValues());
        self::assertSame($command->type, $response->type);
        self::assertSame($command->numberOfSemesters, $response->numberOfSemesters);
        self::assertSame($command->code, $response->code);
    }

    public function testCreateStudyProgrammeWithAlreadyExistingCode(): void
    {
        // assert
        $this->expectException(NonUniquePropertyException::class);

        // arrange
        $studyProgrammeRepository = new FakeStudyProgrammeRepository();
        $uniqueStudyProgrammeCodeChecker = new UniqueStudyProgrammeCodeChecker($studyProgrammeRepository);

        $studyProgrammeWithConflictingCode = StudyProgrammeBuilder::create($uniqueStudyProgrammeCodeChecker)->build();

        $studyProgrammeRepository->withFindByCodeResults($studyProgrammeWithConflictingCode);

        $handler = new CreateStudyProgrammeCommandHandler($studyProgrammeRepository, $uniqueStudyProgrammeCodeChecker);
        $command = new CreateStudyProgrammeCommand(
            name: new TranslationValueDto([
                'de' => 'Deutsch',
                'en' => 'English',
            ]),
            type: StudyProgrammeType::MASTER,
            numberOfSemesters: 4,
            code: $studyProgrammeWithConflictingCode->getCode(),
        );

        // act
        $handler($command);
    }
}
