<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;

final class CategoriesSpaceData extends Data
{
    public ?int $lid = null;
    public ?string $name = null;

    /** @var CategorySpaceData[] $categories */
    public array $categories;
}
