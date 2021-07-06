<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;

final class FormSpaceData extends Data
{
    public int $id;
    public string $name;

    /** @var QuestionSpaceData[] $fields */
    public array $fields;
}
