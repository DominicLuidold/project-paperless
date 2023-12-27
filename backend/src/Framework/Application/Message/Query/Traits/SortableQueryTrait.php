<?php

declare(strict_types=1);

namespace Framework\Application\Message\Query\Traits;

use Symfony\Component\Validator\Constraints as Assert;

trait SortableQueryTrait
{
    final public const DEFAULT_SORT = 'id';
    final public const DEFAULT_ORDER = 'asc';

    #[Assert\NotBlank]
    #[Assert\Choice(callback: 'getSortableFields')]
    public readonly string $sort;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['asc', 'desc'])]
    public readonly string $order;

    /**
     * @return string[]
     */
    abstract public static function getSortableFields(): array;
}
