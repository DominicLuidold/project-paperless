<?php

declare(strict_types=1);

namespace App\StudyProgramme\Application\Message\Response;

use Framework\Application\Message\Response\PaginatedResponse;

/**
 * @extends PaginatedResponse<StudyProgrammeResponse>
 */
final readonly class PaginatedStudyProgrammeResponse extends PaginatedResponse
{
    #[\Override]
    public static function fromData(array $data, int $page, int $limit, int $total): self
    {
        return new self(
            data: $data,
            page: $page,
            limit: $limit,
            total: $total,
        );
    }

    /**
     * @return array<StudyProgrammeResponse>
     */
    #[\Override]
    public function getEmbeddedData(): array
    {
        return $this->data;
    }
}
