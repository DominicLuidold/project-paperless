<?php

declare(strict_types=1);

namespace App\StudyProgramme\Application\Message\QueryHandler;

use App\Common\Application\Exception\NotFoundException;
use App\StudyProgramme\Application\Message\Query\GetStudyProgrammeQuery;
use App\StudyProgramme\Application\Message\Response\StudyProgrammeResponse;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgramme;
use App\StudyProgramme\Domain\Repository\StudyProgrammeRepositoryInterface;
use Framework\Application\Message\QueryHandlerInterface;

final readonly class GetStudyProgrammeQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private StudyProgrammeRepositoryInterface $studyProgrammeRepository,
    ) {
    }

    public function __invoke(GetStudyProgrammeQuery $query): StudyProgrammeResponse
    {
        $studyProgramme = $this->studyProgrammeRepository->findOneById($query->id);
        if (null === $studyProgramme) {
            throw NotFoundException::default(StudyProgramme::class, $query->id);
        }

        return StudyProgrammeResponse::fromEntity($studyProgramme);
    }
}
