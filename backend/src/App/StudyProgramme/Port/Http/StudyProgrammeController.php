<?php

declare(strict_types=1);

namespace App\StudyProgramme\Port\Http;

use App\StudyProgramme\Application\Message\Query\GetStudyProgrammeQuery;
use App\StudyProgramme\Application\Message\Response\StudyProgrammeResponse;
use Framework\Port\Http\ControllerResponseTrait;
use Fusonic\ApiDocumentationBundle\Attribute\DocumentedRoute;
use Fusonic\HttpKernelBundle\Attribute\FromRequest;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: '/study-programmes')]
#[OA\Tag(name: 'StudyProgramme')]
final class StudyProgrammeController extends AbstractController
{
    use ControllerResponseTrait;

    public function __construct(
        private readonly MessageBusInterface $queryBus,
        private readonly MessageBusInterface $commandBus,
        SerializerInterface $serializer,
    ) {
        $this->serializer = $serializer;
    }

    #[DocumentedRoute(path: '/{id}', methods: 'GET', output: StudyProgrammeResponse::class)]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'StudyProgramme not found')]
    public function get(#[FromRequest] GetStudyProgrammeQuery $query): Response
    {
        $envelope = $this->queryBus->dispatch($query);

        return $this->createJsonResponseFromEnvelope($envelope);
    }
}
