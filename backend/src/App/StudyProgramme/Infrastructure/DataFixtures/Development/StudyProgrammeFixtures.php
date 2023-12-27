<?php

declare(strict_types=1);

namespace App\StudyProgramme\Infrastructure\DataFixtures\Development;

use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgramme;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgrammeType;
use App\StudyProgramme\Domain\Service\UniqueStudyProgrammeCodeCheckerInterface;
use Doctrine\Persistence\ObjectManager;
use Framework\Domain\Model\TranslationValueObject;
use Framework\Infrastructure\DataFixtures\DevelopmentFixture;
use Framework\Infrastructure\DataFixtures\ReferenceTrait;

final class StudyProgrammeFixtures extends DevelopmentFixture
{
    use ReferenceTrait;

    private const STUDY_PROGRAMME_COUNT = 10;

    public function __construct(
        private readonly UniqueStudyProgrammeCodeCheckerInterface $uniqueStudyProgrammeCodeChecker,
    ) {
        parent::__construct();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::STUDY_PROGRAMME_COUNT; ++$i) {
            $studyProgramme = StudyProgramme::create(
                // @phpstan-ignore-next-line
                name: TranslationValueObject::create([
                    'de' => $this->faker->words(asText: true),
                    'en' => $this->faker->words(asText: true),
                ]),
                type: $this->faker->randomElement(StudyProgrammeType::class),
                numberOfSemesters: $this->faker->numberBetween(int1: 4, int2: 9),
                code: $this->faker->numerify(),
                uniqueStudyProgrammeCodeChecker: $this->uniqueStudyProgrammeCodeChecker,
            );

            $this->addReference(self::getReferenceName($i), $studyProgramme);
            $manager->persist($studyProgramme);
        }

        $manager->flush();
    }
}
