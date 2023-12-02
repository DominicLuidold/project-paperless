<?php

declare(strict_types=1);

namespace Framework\Port\Http;

use Framework\Domain\Id\UuidEntityId;
use Nelmio\ApiDocBundle\Model\Model;
use Nelmio\ApiDocBundle\ModelDescriber\ModelDescriberInterface;
use OpenApi\Annotations\Schema;
use Symfony\Component\PropertyInfo\Type;

final readonly class UuidEntityIdModelDescriber implements ModelDescriberInterface
{
    public function describe(Model $model, Schema $schema): void
    {
        $type = $model->getType();
        /** @var class-string|null $className */
        $className = $type->getClassName();

        if (null !== $className && is_a($className, UuidEntityId::class, true)) {
            $schema->type = 'string';
            $schema->format = 'uuid';
        }
    }

    public function supports(Model $model): bool
    {
        $type = $model->getType();
        /** @var class-string|null $className */
        $className = $type->getClassName();

        return Type::BUILTIN_TYPE_OBJECT === $type->getBuiltinType()
            && null !== $className
            && is_a($className, UuidEntityId::class, true);
    }
}
