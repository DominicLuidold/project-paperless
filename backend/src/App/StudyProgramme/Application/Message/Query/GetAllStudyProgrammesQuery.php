<?php

declare(strict_types=1);

namespace App\StudyProgramme\Application\Message\Query;

use App\StudyProgramme\Application\Message\Query\Filter\StudyProgrammeFilter;
use Framework\Application\Message\Query\Traits\PaginatedQueryTrait;
use Framework\Application\Message\Query\Traits\SortableQueryTrait;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetAllStudyProgrammesQuery
{
    use PaginatedQueryTrait;
    use SortableQueryTrait;

    public function __construct(
        int $page = self::DEFAULT_PAGE,
        int $limit = self::DEFAULT_LIMIT,
        string $sort = self::DEFAULT_SORT,
        string $order = self::DEFAULT_ORDER,

        #[Assert\Valid]
        public StudyProgrammeFilter $filters = new StudyProgrammeFilter(),
    ) {
        $this->page = $page;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->order = $order;
    }

    /**
     * @return string[]
     */
    #[\Override]
    public static function getSortableFields(): array
    {
        return [
            'id',
            'type',
            'numberOfSemesters',
        ];
    }
}
