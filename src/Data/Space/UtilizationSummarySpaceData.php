<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;

final class UtilizationSummarySpaceData extends Data
{
    public int $active;
    public int $bookableCount;
    public int $totalCount;
}
