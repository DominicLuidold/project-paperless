<?php

declare(strict_types=1);

namespace App\Common\Port\Http;

use App\Common\Application\Utility\StringUtility;
use Doctrine\ORM\EntityNotFoundException;
use Framework\Application\Exception\ApplicationExceptionInterface;
use Fusonic\DDDExtensions\Domain\Exception\DomainExceptionInterface;
use Fusonic\HttpKernelBundle\Exception\ConstraintViolationException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private NormalizerInterface $normalizer,
        #[Autowire('%kernel.debug')]
        private bool $showTrace,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof HandlerFailedException) {
            $previous = $throwable->getPrevious();

            if (null !== $previous) {
                $throwable = $previous;

                $event->setThrowable($throwable);
            }
        }

        $response = $this->mapExceptions($throwable);

        if (null !== $response) {
            $event->setResponse($response);
        }
    }

    private function mapExceptions(\Throwable $throwable): ?Response
    {
        if ($throwable instanceof DomainExceptionInterface
            || $throwable instanceof ApplicationExceptionInterface
        ) {
            $data = $this->formatCustomExceptionResponse($throwable);

            return match ($throwable->getCode()) {
                Response::HTTP_NOT_FOUND => new JsonResponse($data, Response::HTTP_NOT_FOUND),
                Response::HTTP_UNPROCESSABLE_ENTITY => new JsonResponse($data, Response::HTTP_UNPROCESSABLE_ENTITY),
                default => new JsonResponse($data, Response::HTTP_BAD_REQUEST)
            };
        }

        return match (true) {
            $throwable instanceof ConstraintViolationException => new JsonResponse(
                data: $this->normalizer->normalize($throwable),
                status: Response::HTTP_BAD_REQUEST,
            ),
            $throwable instanceof EntityNotFoundException => new JsonResponse(
                data: $this->formatResponse($throwable),
                status: Response::HTTP_NOT_FOUND,
            ),
            $throwable instanceof HttpExceptionInterface => new JsonResponse(
                data: $this->formatHttpException($throwable),
                status: $throwable->getStatusCode(),
            ),
            default => null,
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function formatHttpException(HttpExceptionInterface $exception): array
    {
        $data = $this->formatResponse($exception);

        $data['code'] = 0 === $exception->getCode() ? $exception->getStatusCode() : $exception->getCode();

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function formatResponse(\Throwable $exception): array
    {
        $data = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ];

        if ($this->showTrace) {
            $data['trace'] = $exception->getTrace();
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function formatCustomExceptionResponse(
        DomainExceptionInterface|ApplicationExceptionInterface $exception
    ): array {
        $data = $this->formatResponse($exception);

        if (0 === $exception->getCode()) {
            unset($data['code']);
        }

        $data['title'] = StringUtility::getClassBasename($exception::class);

        return $data;
    }
}
