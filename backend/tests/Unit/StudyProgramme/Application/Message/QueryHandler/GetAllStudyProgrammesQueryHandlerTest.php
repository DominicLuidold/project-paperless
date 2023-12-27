<?php

declare(strict_types=1);

namespace App\Tests\Unit\StudyProgramme\Application\Message\QueryHandler;

use App\StudyProgramme\Application\Message\Query\GetAllStudyProgrammesQuery;
use App\StudyProgramme\Application\Message\QueryHandler\GetAllStudyProgrammesQueryHandler;
use App\StudyProgramme\Application\Service\UniqueStudyProgrammeCodeChecker;
use App\Tests\Unit\StudyProgramme\Domain\Model\StudyProgrammeBuilder;
use App\Tests\Unit\StudyProgramme\Infrastructure\Repository\FakeStudyProgrammeRepository;
use PHPUnit\Framework\TestCase;

final class GetAllStudyProgrammesQueryHandlerTest extends TestCase
{
    public function testGetStudyProgrammes(): void
    {
        // arrange
        $studyProgrammeRepository = new FakeStudyProgrammeRepository();
        $uniqueStudyProgrammeChecker = new UniqueStudyProgrammeCodeChecker($studyProgrammeRepository);

        $studyProgramme1 = StudyProgrammeBuilder::create($uniqueStudyProgrammeChecker)->build();
        $studyProgramme2 = StudyProgrammeBuilder::create($uniqueStudyProgrammeChecker)->build();

        $studyProgrammeRepository->withFindByFilterResults([$studyProgramme1, $studyProgramme2]);
        $studyProgrammeRepository->withCountWithFilterResult(2);

        $handler = new GetAllStudyProgrammesQueryHandler($studyProgrammeRepository);
        $query = new GetAllStudyProgrammesQuery();

        // act
        $response = $handler($query);

        // assert
        self::assertSame($query->page, $response->page);
        self::assertSame($query->limit, $response->limit);
        self::assertSame(1, $response->pages);
        self::assertSame(2, $response->total);
        self::assertCount(2, $response->getEmbeddedData());

        self::assertSame($studyProgramme1->getId(), $response->getEmbeddedData()[0]->id);
        self::assertSame($studyProgramme1->getName()->getValues(), $response->getEmbeddedData()[0]->name->getValues());
        self::assertSame($studyProgramme1->getType(), $response->getEmbeddedData()[0]->type);
        self::assertSame($studyProgramme1->getNumberOfSemesters(), $response->getEmbeddedData()[0]->numberOfSemesters);
        self::assertSame($studyProgramme1->getCode(), $response->getEmbeddedData()[0]->code);

        self::assertSame($studyProgramme2->getId(), $response->getEmbeddedData()[1]->id);
        self::assertSame($studyProgramme2->getName()->getValues(), $response->getEmbeddedData()[1]->name->getValues());
        self::assertSame($studyProgramme2->getType(), $response->getEmbeddedData()[1]->type);
        self::assertSame($studyProgramme2->getNumberOfSemesters(), $response->getEmbeddedData()[1]->numberOfSemesters);
        self::assertSame($studyProgramme2->getCode(), $response->getEmbeddedData()[1]->code);
    }
}
