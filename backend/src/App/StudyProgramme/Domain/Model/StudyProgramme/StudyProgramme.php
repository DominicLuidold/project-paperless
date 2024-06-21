<?php

declare(strict_types=1);

namespace App\StudyProgramme\Domain\Model\StudyProgramme;

use App\Common\Domain\Id\StudyProgrammeId;
use App\StudyProgramme\Domain\Service\UniqueStudyProgrammeCodeCheckerInterface;
use Framework\Domain\Model\TranslationValueObject;
use Fusonic\DDDExtensions\Domain\Model\AggregateRoot;
use Fusonic\DDDExtensions\Domain\Validation\Assert;

class StudyProgramme extends AggregateRoot
{
    private function __construct(
        private readonly StudyProgrammeId $id,
        private TranslationValueObject $name,
        private StudyProgrammeType $type,
        private int $numberOfSemesters,
        private string $code,
    ) {
        Assert::lazy($this)
            ->that($this->numberOfSemesters, 'numberOfSemesters')->range(4, 9)
            ->that($this->code, 'code')->notBlank();
    }

    public static function create(
        TranslationValueObject $name,
        StudyProgrammeType $type,
        int $numberOfSemesters,
        string $code,
        UniqueStudyProgrammeCodeCheckerInterface $uniqueStudyProgrammeCodeChecker,
        StudyProgrammeId $id = new StudyProgrammeId(),
    ): self {
        $uniqueStudyProgrammeCodeChecker->checkUniqueStudyProgrammeCode($code);

        return new self(
            id: $id,
            name: $name,
            type: $type,
            numberOfSemesters: $numberOfSemesters,
            code: $code,
        );
    }

    #[\Override]
    public function getId(): StudyProgrammeId
    {
        return $this->id;
    }

    public function getName(): TranslationValueObject
    {
        return $this->name;
    }

    public function setName(TranslationValueObject $name): void
    {
        $this->name = $name;
    }

    public function getType(): StudyProgrammeType
    {
        return $this->type;
    }

    public function setType(StudyProgrammeType $type): void
    {
        $this->type = $type;
    }

    public function getNumberOfSemesters(): int
    {
        return $this->numberOfSemesters;
    }

    public function setNumberOfSemesters(int $numberOfSemesters): void
    {
        $this->numberOfSemesters = $numberOfSemesters;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function updateCode(
        string $code,
        UniqueStudyProgrammeCodeCheckerInterface $uniqueStudyProgrammeCodeChecker
    ): void {
        if ($code !== $this->code) {
            $uniqueStudyProgrammeCodeChecker->checkUniqueStudyProgrammeCode($code);
        }

        $this->code = $code;
    }
}
