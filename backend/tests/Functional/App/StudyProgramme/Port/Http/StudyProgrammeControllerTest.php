<?php

declare(strict_types=1);

namespace App\Tests\Functional\App\StudyProgramme\Port\Http;

use App\StudyProgramme\Infrastructure\DataFixtures\Test\StudyProgrammeFixtures;
use App\Tests\Test\WebDatabaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class StudyProgrammeControllerTest extends WebDatabaseTestCase
{
    public function testGetStudyProgrammes(): void
    {
        $this->makeJsonRequest(Request::METHOD_GET, '/api/study-programmes');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertQueryCount(4);

        $response = self::getJsonResponse();

        $this->validatePaginatedResponse($response);
        self::assertCount(3, $response['_embedded']);
        self::assertSame(1, $response['page']);
        self::assertSame(30, $response['limit']);
        self::assertSame(1, $response['pages']);
        self::assertSame(3, $response['total']);

        $studyProgramme1 = $response['_embedded'][0];
        self::assertSame('018c4a1b-aee5-76c3-87d5-ca2b31340546', $studyProgramme1['id']);
        self::assertSame(
            expected: [
                'de' => 'Informatik – Software and Information Engineering',
                'en' => 'Computer Science - Software and Information Engineering',
            ],
            actual: $studyProgramme1['name']
        );
        self::assertSame('BACHELOR', $studyProgramme1['type']);
        self::assertSame(6, $studyProgramme1['numberOfSemesters']);
        self::assertSame('0247', $studyProgramme1['code']);
    }

    public function testGetStudyProgrammesWithTypeFilter(): void
    {
        $this->makeJsonRequest(Request::METHOD_GET, '/api/study-programmes?filters[type]=BACHELOR');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertQueryCount(4);

        $response = self::getJsonResponse();
        self::assertCount(2, $response['_embedded']);
        self::assertSame('018c4a1b-aee5-76c3-87d5-ca2b31340546', $response['_embedded'][0]['id']);
        self::assertSame('018c4a1b-aee5-7999-a84e-fa116e82ed8a', $response['_embedded'][1]['id']);

        self::$client->enableProfiler();
        $this->makeJsonRequest(Request::METHOD_GET, '/api/study-programmes?filters[type]=MASTER');
        self::assertResponseStatusCodeSame(200);
        self::assertQueryCount(4);

        $response = self::getJsonResponse();
        self::assertCount(1, $response['_embedded']);
        self::assertSame('018c4a1b-aee5-7eb4-ac62-cf704aa4b8e3', $response['_embedded'][0]['id']);
    }

    public function testGetStudyProgrammesWithSortAndOrder(): void
    {
        $this->makeJsonRequest(Request::METHOD_GET, '/api/study-programmes?sort=type&order=desc');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertQueryCount(4);

        $response = self::getJsonResponse();
        self::assertCount(3, $response['_embedded']);
        self::assertSame('018c4a1b-aee5-7eb4-ac62-cf704aa4b8e3', $response['_embedded'][0]['id']);
        self::assertSame('018c4a1b-aee5-76c3-87d5-ca2b31340546', $response['_embedded'][1]['id']);
        self::assertSame('018c4a1b-aee5-7999-a84e-fa116e82ed8a', $response['_embedded'][2]['id']);
    }

    public function testGetStudyProgrammesWithPagination(): void
    {
        $this->makeJsonRequest(Request::METHOD_GET, '/api/study-programmes?page=2&limit=1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertQueryCount(4);

        $response = self::getJsonResponse();
        $this->validatePaginatedResponse($response);
        self::assertCount(1, $response['_embedded']);
        self::assertSame(2, $response['page']);
        self::assertSame(1, $response['limit']);
        self::assertSame(3, $response['pages']);
        self::assertSame(3, $response['total']);
        self::assertSame('018c4a1b-aee5-7999-a84e-fa116e82ed8a', $response['_embedded'][0]['id']);
    }

    public function testGetStudyProgramme(): void
    {
        $this->makeJsonRequest(Request::METHOD_GET, '/api/study-programmes/'.StudyProgrammeFixtures::STUDY_PROGRAMME_1_ID);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
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
        $this->makeJsonRequest(Request::METHOD_GET, '/api/study-programmes/some-random-string');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        self::assertQueryCount(2);
    }

    public function testCreateStudyProgramme(): void
    {
        $data = $this->getData();

        $this->makeJsonRequest(Request::METHOD_POST, '/api/study-programmes/create', $data);
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertQueryCount(7);

        $response = self::getJsonResponse();
        self::assertArrayHasKey('id', $response);
        self::assertSame($data['name'], $response['name']);
        self::assertSame($data['type'], $response['type']);
        self::assertSame($data['numberOfSemesters'], $response['numberOfSemesters']);
        self::assertSame($data['code'], $response['code']);
    }

    public function testCreateStudyProgrammeWithAlreadyExistingCode(): void
    {
        $data = $this->getData();

        $this->makeJsonRequest(Request::METHOD_POST, '/api/study-programmes/create', $data);
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertQueryCount(7);

        self::$client->enableProfiler();
        $this->makeJsonRequest(Request::METHOD_POST, '/api/study-programmes/create', $data);
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertQueryCount(4);

        $response = self::getJsonResponse();
        self::assertSame(
            expected: 'An entity for class `StudyProgramme` with `code=01234` already exists.',
            actual: $response['message']
        );
        self::assertSame(422, $response['code']);
    }

    public function testUpdateStudyProgramme(): void
    {
        $data = $this->getData();

        $this->makeJsonRequest(
            method: Request::METHOD_POST,
            uri: '/api/study-programmes/'.StudyProgrammeFixtures::STUDY_PROGRAMME_1_ID.'/update',
            data: $data
        );
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertQueryCount(8);

        $response = self::getJsonResponse();
        self::assertArrayHasKey('id', $response);
        self::assertSame($data['name'], $response['name']);
        self::assertSame($data['type'], $response['type']);
        self::assertSame($data['numberOfSemesters'], $response['numberOfSemesters']);
        self::assertSame($data['code'], $response['code']);
    }

    public function testUpdateStudyProgrammeWithAlreadyExistingCode(): void
    {
        $data = $this->getData();
        $data['code'] = StudyProgrammeFixtures::getData()[StudyProgrammeFixtures::STUDY_PROGRAMME_2_ID]['code'];

        $this->makeJsonRequest(
            method: Request::METHOD_POST,
            uri: '/api/study-programmes/'.StudyProgrammeFixtures::STUDY_PROGRAMME_1_ID.'/update',
            data: $data
        );
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertQueryCount(5);

        $response = self::getJsonResponse();
        self::assertSame(
            expected: 'An entity for class `StudyProgramme` with `code=0816` already exists.',
            actual: $response['message']
        );
        self::assertSame(422, $response['code']);
    }

    /**
     * @return array<string, int|string|array<string, string>>
     */
    private function getData(): array
    {
        return [
            'name' => [
                'de' => 'Beispiel Studiengang',
                'en' => 'Sample Study Programme',
            ],
            'type' => 'MASTER',
            'numberOfSemesters' => 4,
            'code' => '01234',
        ];
    }
}
