<?php

declare(strict_types=1);

namespace Framework\Port\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

trait ControllerResponseTrait
{
    private readonly SerializerInterface $serializer;

    public function createJsonResponseFromEnvelope(Envelope $envelope, int $status = Response::HTTP_OK): JsonResponse
    {
        /** @var HandledStamp $stamp */
        $stamp = $envelope->last(HandledStamp::class);
        $result = $stamp->getResult();

        $data = $this->serializer->serialize(
            data: $result,
            format: JsonEncoder::FORMAT,
            context: [
                JsonEncode::OPTIONS => JsonResponse::DEFAULT_ENCODING_OPTIONS |
                    \JSON_PRETTY_PRINT |
                    \JSON_UNESCAPED_UNICODE |
                    \JSON_PRESERVE_ZERO_FRACTION,
            ],
        );

        return JsonResponse::fromJsonString($data, $status);
    }
}
