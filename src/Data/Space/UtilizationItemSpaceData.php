<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;

final class UtilizationItemSpaceData extends Data
{
    public int $id;
    public string $name;
    public bool $bookableAsWhole;
    public int $currentOccupancy;
    public int $currentCapacity;
    public int $maxCapacity;
}
