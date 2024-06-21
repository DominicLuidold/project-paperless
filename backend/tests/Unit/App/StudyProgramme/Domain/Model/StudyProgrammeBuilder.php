<?php

declare(strict_types=1);

namespace App\Tests\Unit\App\StudyProgramme\Domain\Model;

use App\Common\Domain\Id\StudyProgrammeId;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgramme;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgrammeType;
use App\StudyProgramme\Domain\Service\UniqueStudyProgrammeCodeCheckerInterface;
use Framework\Domain\Model\TranslationValueObject;

final class StudyProgrammeBuilder
{
    private StudyProgrammeId $id;
    private TranslationValueObject $name;
    private StudyProgrammeType $type = StudyProgrammeType::BACHELOR;
    private int $numberOfSemesters = 6;
    private string $code = '01234';
    private UniqueStudyProgrammeCodeCheckerInterface $uniqueStudyProgrammeCodeChecker;

    private function __construct()
    {
        $this->id = new StudyProgrammeId();
        $this->name = TranslationValueObject::create([
            'de' => 'Deutsch',
            'en' => 'English',
        ]);
    }

    public static function create(UniqueStudyProgrammeCodeCheckerInterface $uniqueStudyProgrammeCodeChecker): self
    {
        $factory = new self();
        $factory->uniqueStudyProgrammeCodeChecker = $uniqueStudyProgrammeCodeChecker;

        return $factory;
    }

    public function build(): StudyProgramme
    {
        return StudyProgramme::create(
            name: $this->name,
            type: $this->type,
            numberOfSemesters: $this->numberOfSemesters,
            code: $this->code,
            uniqueStudyProgrammeCodeChecker: $this->uniqueStudyProgrammeCodeChecker,
            id: $this->id,
        );
    }

    public function setId(StudyProgrammeId $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setName(TranslationValueObject $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setType(StudyProgrammeType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setNumberOfSemesters(int $numberOfSemesters): self
    {
        $this->numberOfSemesters = $numberOfSemesters;

        return $this;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
