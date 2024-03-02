<?php

declare(strict_types=1);

namespace Framework\Application\Exception;

use Symfony\Component\Messenger\Exception\UnrecoverableExceptionInterface;

/**
 * An exception thrown from the application layer should implement this interface.
 */
interface ApplicationExceptionInterface extends \Throwable, UnrecoverableExceptionInterface
{
}
