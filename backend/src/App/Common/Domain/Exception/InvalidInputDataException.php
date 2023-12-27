<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception;

use Fusonic\DDDExtensions\Domain\Exception\DomainExceptionInterface;

class InvalidInputDataException extends \RuntimeException implements DomainExceptionInterface
{
}
