<?php

declare(strict_types=1);

namespace App\StudyProgramme\Infrastructure\DataFixtures\Test;

use App\Common\Domain\Id\StudyProgrammeId;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgramme;
use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgrammeType;
use App\StudyProgramme\Domain\Service\UniqueStudyProgrammeCodeCheckerInterface;
use Doctrine\Persistence\ObjectManager;
use Framework\Domain\Model\TranslationValueObject;
use Framework\Infrastructure\DataFixtures\ReferenceTrait;
use Framework\Infrastructure\DataFixtures\TestFixture;
use Symfony\Component\Uid\Uuid;

/**
 * @phpstan-type StudyProgrammeFixtureData array{
 *     name: array<string, string>,
 *     type: StudyProgrammeType,
 *     numberOfSemesters: int,
 *     code: string
 * }
 */
final class StudyProgrammeFixtures extends TestFixture
{
    use ReferenceTrait;

    public const string STUDY_PROGRAMME_1_ID = '018c4a1b-aee5-76c3-87d5-ca2b31340546';
    public const string STUDY_PROGRAMME_2_ID = '018c4a1b-aee5-7999-a84e-fa116e82ed8a';
    public const string STUDY_PROGRAMME_3_ID = '018c4a1b-aee5-7eb4-ac62-cf704aa4b8e3';

    /**
     * @return array<string, StudyProgrammeFixtureData>
     */
    #[\Override]
    public static function getData(): array
    {
        return [
            self::STUDY_PROGRAMME_1_ID => [
                'name' => [
                    'de' => 'Informatik – Software and Information Engineering',
                    'en' => 'Computer Science - Software and Information Engineering',
                ],
                'type' => StudyProgrammeType::BACHELOR,
                'numberOfSemesters' => 6,
                'code' => '0247',
            ],
            self::STUDY_PROGRAMME_2_ID => [
                'name' => [
                    'de' => 'Gesundheits- und Krankenpflege',
                    'en' => 'Health Care and Nursing',
                ],
                'type' => StudyProgrammeType::BACHELOR,
                'numberOfSemesters' => 6,
                'code' => '0816',
            ],
            self::STUDY_PROGRAMME_3_ID => [
                'name' => [
                    'de' => 'Informatik',
                    'en' => 'Computer Science',
                ],
                'type' => StudyProgrammeType::MASTER,
                'numberOfSemesters' => 4,
                'code' => '0249',
            ],
        ];
    }

    public function __construct(
        private readonly UniqueStudyProgrammeCodeCheckerInterface $uniqueStudyProgrammeCodeChecker,
    ) {
    }

    #[\Override]
    public function load(ObjectManager $manager): void
    {
        foreach (self::getData() as $id => $data) {
            $studyProgramme = StudyProgramme::create(
                name: TranslationValueObject::create($data['name']),
                type: $data['type'],
                numberOfSemesters: $data['numberOfSemesters'],
                code: $data['code'],
                uniqueStudyProgrammeCodeChecker: $this->uniqueStudyProgrammeCodeChecker,
                id: new StudyProgrammeId(Uuid::fromString($id)),
            );

            $this->addReference(self::getReferenceName($id), $studyProgramme);
            $manager->persist($studyProgramme);
        }

        $manager->flush();
    }
}
