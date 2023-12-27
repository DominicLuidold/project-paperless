<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Types;

use App\Common\Domain\Id\StudyProgrammeId;
use Framework\Infrastructure\Types\UuidEntityIdType;

final class StudyProgrammeIdType extends UuidEntityIdType
{
    public const NAME = 'study_programme_uuid';

    public function getName(): string
    {
        return self::NAME;
    }

    protected function getTypeClass(): string
    {
        return StudyProgrammeId::class;
    }
}
