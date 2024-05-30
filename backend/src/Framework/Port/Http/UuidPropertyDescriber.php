<?php

declare(strict_types=1);

namespace Framework\Port\Http;

use Nelmio\ApiDocBundle\PropertyDescriber\PropertyDescriberInterface;
use OpenApi\Annotations\Schema;
use Symfony\Component\Uid\Uuid;

final readonly class UuidPropertyDescriber implements PropertyDescriberInterface
{
    /**
     * @param array<string, mixed> $context
     */
    #[\Override]
    public function describe(
        array $types,
        Schema $property,
        ?array $groups = null,
        ?Schema $schema = null,
        array $context = []
    ): void {
        $property->type = 'string';
        $property->format = 'uuid';
    }

    #[\Override]
    public function supports(array $types): bool
    {
        return 1 === \count($types)
            && null !== $types[0]->getClassName()
            && is_a($types[0]->getClassName(), Uuid::class, true);
    }
}
