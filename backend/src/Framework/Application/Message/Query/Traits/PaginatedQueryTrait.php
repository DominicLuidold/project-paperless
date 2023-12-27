<?php

declare(strict_types=1);

namespace Framework\Application\Message\Query\Traits;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

trait PaginatedQueryTrait
{
    final public const DEFAULT_PAGE = 1;
    final public const DEFAULT_LIMIT = 30;

    #[Assert\NotNull]
    #[Assert\Positive]
    public readonly int $page;

    #[OA\Property(example: self::DEFAULT_LIMIT)]
    #[Assert\NotNull]
    #[Assert\Positive]
    public readonly int $limit;

    public function offset(): int
    {
        return max(($this->page - 1) * $this->limit, 0);
    }
}
