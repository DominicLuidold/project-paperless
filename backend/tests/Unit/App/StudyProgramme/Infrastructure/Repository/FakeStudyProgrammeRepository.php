<?php

declare(strict_types=1);

namespace App\Tests\Unit\App\StudyProgramme\Infrastructure\Repository;

use App\Common\Domain\Id\StudyProgrammeId;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgramme;
use App\StudyProgramme\Domain\Repository\StudyProgrammeRepositoryFilter;
use App\StudyProgramme\Domain\Repository\StudyProgrammeRepositoryInterface;
use Framework\Infrastructure\Repository\QueryOptions;

final class FakeStudyProgrammeRepository implements StudyProgrammeRepositoryInterface
{
    /**
     * @var StudyProgramme[]
     */
    private array $findOneByIdResults = [];

    /**
     * @var StudyProgramme[]
     */
    private array $findOneByCodeResults = [];

    /**
     * @var StudyProgramme[]
     */
    private array $findByFilterResults = [];

    private int $countByFilterResult = 0;

    #[\Override]
    public function findOneById(StudyProgrammeId $id): ?StudyProgramme
    {
        if ([] === $this->findOneByIdResults) {
            return null;
        }

        return array_shift($this->findOneByIdResults);
    }

    public function withFindByIdResults(StudyProgramme ...$studyProgramme): self
    {
        $this->findOneByIdResults = $studyProgramme;

        return $this;
    }

    #[\Override]
    public function findOneByCode(string $code): ?StudyProgramme
    {
        if ([] === $this->findOneByCodeResults) {
            return null;
        }

        return array_shift($this->findOneByCodeResults);
    }

    public function withFindByCodeResults(StudyProgramme ...$studyProgramme): self
    {
        $this->findOneByCodeResults = $studyProgramme;

        return $this;
    }

    #[\Override]
    public function findByFilter(
        StudyProgrammeRepositoryFilter $filter = new StudyProgrammeRepositoryFilter(),
        QueryOptions $options = new QueryOptions()
    ): array {
        return $this->findByFilterResults;
    }

    /**
     * @param StudyProgramme[] $studyProgrammes
     */
    public function withFindByFilterResults(array $studyProgrammes): self
    {
        $this->findByFilterResults = $studyProgrammes;

        return $this;
    }

    #[\Override]
    public function countWithFilter(StudyProgrammeRepositoryFilter $filter): int
    {
        return $this->countByFilterResult;
    }

    public function withCountWithFilterResult(int $count): self
    {
        $this->countByFilterResult = $count;

        return $this;
    }

    #[\Override]
    public function saveEntity(StudyProgramme $entity): void
    {
    }
}
