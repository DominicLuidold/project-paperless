<?php

declare(strict_types=1);

namespace Framework\Infrastructure\Repository\Doctrine;

trait SortingTrait
{
    private ?string $sort = null;
    private ?string $order = null;

    /**
     * @return array<string, string>
     */
    abstract public static function getSortableFieldsMapping(): array;

    public function getMappedSort(?string $unmappedSort): ?string
    {
        return self::getSortableFieldsMapping()[$unmappedSort] ?? null;
    }

    public function setSort(?string $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function setOrder(?string $order): self
    {
        $this->order = $order;

        return $this;
    }
}
