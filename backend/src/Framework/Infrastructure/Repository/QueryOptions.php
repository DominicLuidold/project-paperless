<?php

declare(strict_types=1);

namespace Framework\Infrastructure\Repository;

use Fusonic\DDDExtensions\Domain\Validation\Assert;

final readonly class QueryOptions
{
    private const ORDER_VALUES = ['asc', 'desc'];

    public function __construct(
        public ?int $offset = null,
        public ?int $limit = null,
        public string $sort = 'id',
        public string $order = 'asc',
    ) {
        Assert::that(self::class, 'order', $this->order)->choice(self::ORDER_VALUES);
    }
}
