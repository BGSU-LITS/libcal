<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;

final class ZoneSpaceData extends Data
{
    public int $id;
    public string $name;
    public ?string $description = null;

    /** @var int[]|null $itemIds */
    public ?array $itemIds = null;

    /** @var UtilizationItemSpaceData[]|null $spaces */
    public ?array $spaces = null;
}
