<?php

declare(strict_types=1);

namespace Framework\Application\Message\Response;

use Symfony\Component\Serializer\Attribute\SerializedName;

/**
 * @template T of object
 */
abstract readonly class PaginatedResponse
{
    public int $pages;

    /**
     * @param array<T> $data
     */
    protected function __construct(
        protected array $data,
        public int $page,
        public int $limit,
        public int $total,
    ) {
        $this->pages = max((int) ceil($total / $limit), 1);
    }

    /**
     * @return array<T>
     */
    #[SerializedName('_embedded')]
    abstract public function getEmbeddedData(): array;

    /**
     * @param array<T> $data
     *
     * @return self<T>
     */
    abstract public static function fromData(array $data, int $page, int $limit, int $total): self;
}
