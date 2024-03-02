<?php

declare(strict_types=1);

namespace App\StudyProgramme\Application\Message\QueryHandler;

use App\StudyProgramme\Application\Message\Query\GetAllStudyProgrammesQuery;
use App\StudyProgramme\Application\Message\Response\PaginatedStudyProgrammeResponse;
use App\StudyProgramme\Application\Message\Response\StudyProgrammeResponse;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgramme;
use App\StudyProgramme\Domain\Repository\StudyProgrammeRepositoryFilter;
use App\StudyProgramme\Domain\Repository\StudyProgrammeRepositoryInterface;
use Framework\Application\Message\QueryHandlerInterface;
use Framework\Infrastructure\Repository\QueryOptions;

final readonly class GetAllStudyProgrammesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private StudyProgrammeRepositoryInterface $studyProgrammeRepository,
    ) {
    }

    public function __invoke(GetAllStudyProgrammesQuery $query): PaginatedStudyProgrammeResponse
    {
        $filter = new StudyProgrammeRepositoryFilter(type: $query->filters->type);

        $studyProgrammes = $this->studyProgrammeRepository->findAllByFilter(
            filter: $filter,
            options: new QueryOptions(
                offset: $query->offset(),
                limit: $query->limit,
                sort: $query->sort,
                order: $query->order
            )
        );
        $totalCount = $this->studyProgrammeRepository->countWithFilter($filter);

        return PaginatedStudyProgrammeResponse::fromData(
            data: array_map(
                callback: static fn (StudyProgramme $studyProgramme): StudyProgrammeResponse => StudyProgrammeResponse::fromEntity($studyProgramme),
                array: $studyProgrammes,
            ),
            page: $query->page,
            limit: $query->limit,
            total: $totalCount,
        );
    }
}
