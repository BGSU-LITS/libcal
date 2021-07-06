<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;

final class QuestionSpaceData extends Data
{
    public ?int $id = null;
    public string $label;
    public string $type;
    public bool $required;

    /** @var string[]|null $options */
    public ?array $options = null;
}
