<?php

declare(strict_types=1);

namespace Framework\Domain\Id;

use Fusonic\DDDExtensions\Domain\Model\EntityId;
use Fusonic\DDDExtensions\Domain\Model\ValueObject;
use Symfony\Component\Uid\Uuid;

abstract class UuidEntityId extends EntityId
{
    private readonly Uuid $id;

    public function __construct(Uuid $id = null)
    {
        $this->id = $id ?? Uuid::v7();
    }

    #[\Override]
    public function __toString(): string
    {
        return (string) $this->id;
    }

    #[\Override]
    public function isDefined(): bool
    {
        return true;
    }

    #[\Override]
    public function getValue(): Uuid
    {
        return $this->id;
    }

    #[\Override]
    public function equals(ValueObject $object): bool
    {
        return $object instanceof self
            && $this->id->equals($object->id);
    }
}
