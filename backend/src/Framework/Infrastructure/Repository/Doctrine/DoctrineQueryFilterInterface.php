<?php

declare(strict_types=1);

namespace Framework\Infrastructure\Repository\Doctrine;

use Doctrine\ORM\QueryBuilder;

interface DoctrineQueryFilterInterface
{
    public function updateQueryBuilder(QueryBuilder $qb): QueryBuilder;
}
