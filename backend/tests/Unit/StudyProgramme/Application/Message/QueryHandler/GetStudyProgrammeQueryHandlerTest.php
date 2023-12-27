<?php

declare(strict_types=1);

namespace App\Tests\Unit\StudyProgramme\Application\Message\QueryHandler;

use App\Common\Application\Exception\NotFoundException;
use App\Common\Domain\Id\StudyProgrammeId;
use App\StudyProgramme\Application\Message\Query\GetStudyProgrammeQuery;
use App\StudyProgramme\Application\Message\QueryHandler\GetStudyProgrammeQueryHandler;
use App\StudyProgramme\Application\Service\UniqueStudyProgrammeCodeChecker;
use App\Tests\Unit\StudyProgramme\Domain\Model\StudyProgrammeBuilder;
use App\Tests\Unit\StudyProgramme\Infrastructure\Repository\FakeStudyProgrammeRepository;
use PHPUnit\Framework\TestCase;

final class GetStudyProgrammeQueryHandlerTest extends TestCase
{
    public function testGetStudyProgramme(): void
    {
        // arrange
        $studyProgrammeRepository = new FakeStudyProgrammeRepository();
        $uniqueStudyProgrammeChecker = new UniqueStudyProgrammeCodeChecker($studyProgrammeRepository);

        $studyProgramme = StudyProgrammeBuilder::create($uniqueStudyProgrammeChecker)->build();

        $studyProgrammeRepository->withFindByIdResults($studyProgramme);

        $handler = new GetStudyProgrammeQueryHandler($studyProgrammeRepository);
        $query = new GetStudyProgrammeQuery(id: $studyProgramme->getId());

        // act
        $response = $handler($query);

        // assert
        self::assertSame($studyProgramme->getId(), $response->id);
        self::assertSame($studyProgramme->getName()->getValues(), $response->name->getValues());
        self::assertSame($studyProgramme->getType(), $response->type);
        self::assertSame($studyProgramme->getNumberOfSemesters(), $response->numberOfSemesters);
        self::assertSame($studyProgramme->getCode(), $response->code);
    }

    public function testGetNotExistingStudyProgramme(): void
    {
        // assert
        $this->expectException(NotFoundException::class);

        // arrange
        $studyProgrammeRepository = new FakeStudyProgrammeRepository();

        $handler = new GetStudyProgrammeQueryHandler($studyProgrammeRepository);
        $query = new GetStudyProgrammeQuery(id: new StudyProgrammeId());

        // act
        $handler($query);
    }
}
