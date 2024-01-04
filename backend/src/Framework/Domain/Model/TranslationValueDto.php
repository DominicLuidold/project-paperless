<?php

declare(strict_types=1);

namespace Framework\Domain\Model;

final readonly class TranslationValueDto
{
    /**
     * @param array<string, string> $values
     */
    public function __construct(
        public array $values,
    ) {
    }
}
