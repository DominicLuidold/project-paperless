<?php

declare(strict_types=1);

namespace App\Tests\Functional\StudyProgramme\Port\Http;

use App\StudyProgramme\Infrastructure\DataFixtures\Test\StudyProgrammeFixtures;
use App\Tests\Test\WebDatabaseTestCase;

final class StudyProgrammeControllerTest extends WebDatabaseTestCase
{
    public function testGetStudyProgramme(): void
    {
        $this->makeJsonRequest('GET', '/api/study-programmes/'.StudyProgrammeFixtures::STUDY_PROGRAMME_1_ID);
        self::assertResponseStatusCodeSame(200);
        self::assertQueryCount(3);

        $response = self::getJsonResponse();
        self::assertSame('018c4a1b-aee5-76c3-87d5-ca2b31340546', $response['id']);
        self::assertSame(
            expected: [
                'de' => 'Informatik – Software and Information Engineering',
                'en' => 'Computer Science - Software and Information Engineering',
            ],
            actual: $response['name']
        );
        self::assertSame('BACHELOR', $response['type']);
        self::assertSame(6, $response['numberOfSemesters']);
        self::assertSame('0247', $response['code']);
    }

    public function testGetNotExistingStudyProgramme(): void
    {
        $this->makeJsonRequest('GET', '/api/study-programmes/some-random-string');
        self::assertResponseStatusCodeSame(404);
        self::assertQueryCount(2);
    }
}
