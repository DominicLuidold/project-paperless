<?php

declare(strict_types=1);

namespace Framework\Port\Http;

use Nelmio\ApiDocBundle\PropertyDescriber\NullablePropertyTrait;
use Nelmio\ApiDocBundle\PropertyDescriber\PropertyDescriberInterface;
use OpenApi\Annotations\Schema;
use Symfony\Component\Uid\Uuid;

final readonly class UuidPropertyDescriber implements PropertyDescriberInterface
{
    // Ignore deprecation until https://github.com/nelmio/NelmioApiDocBundle/pull/2098 is merged
    // @phpstan-ignore-next-line
    use NullablePropertyTrait;

    /**
     * @param array<mixed>|null $groups
     */
    #[\Override]
    public function describe(array $types, Schema $property, array $groups = null, Schema $schema = null): void
    {
        $property->type = 'string';
        $this->setNullableProperty($types[0], $property, $schema);
    }

    #[\Override]
    public function supports(array $types): bool
    {
        return 1 === \count($types)
            && null !== $types[0]->getClassName()
            && is_a($types[0]->getClassName(), Uuid::class, true);
    }
}
