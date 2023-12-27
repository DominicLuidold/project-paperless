<?php

declare(strict_types=1);

namespace App\StudyProgramme\Application\Message\Query\Filter;

use App\StudyProgramme\Domain\Model\StudyProgramme\StudyProgrammeType;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class StudyProgrammeFilter
{
    public function __construct(
        #[Assert\NotBlank(allowNull: true)]
        public ?StudyProgrammeType $type = null,
    ) {
    }
}
