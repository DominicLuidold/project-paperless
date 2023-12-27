<?php

declare(strict_types=1);

namespace Framework\Port\Http;

use Framework\Domain\Model\TranslationValueDto;
use Framework\Domain\Model\TranslationValueObject;
use Nelmio\ApiDocBundle\Model\Model;
use Nelmio\ApiDocBundle\ModelDescriber\ModelDescriberInterface;
use OpenApi\Annotations\Schema;
use Symfony\Component\PropertyInfo\Type;

final readonly class TranslationValueModelDescriber implements ModelDescriberInterface
{
    public function describe(Model $model, Schema $schema): void
    {
        $type = $model->getType();
        /** @var class-string|null $className */
        $className = $type->getClassName();

        if (null !== $className && (is_a($className, TranslationValueObject::class, true)
            || is_a($className, TranslationValueDto::class, true))
        ) {
            $schema->type = 'TranslationValue';
            $schema->example = new class() {
                public function __construct(
                    public readonly string $de = 'string',
                    public readonly string $en = 'string',
                ) {
                }
            };
        }
    }

    public function supports(Model $model): bool
    {
        $type = $model->getType();
        /** @var class-string|null $className */
        $className = $type->getClassName();

        return Type::BUILTIN_TYPE_OBJECT === $type->getBuiltinType()
            && null !== $className
            && (is_a($className, TranslationValueObject::class, true)
                || is_a($className, TranslationValueDto::class, true));
    }
}
