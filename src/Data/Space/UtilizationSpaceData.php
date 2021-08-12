<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;

final class UtilizationSpaceData extends Data
{
    public UtilizationSummarySpaceData $seatSummary;
    public UtilizationSummarySpaceData $spaceSummary;

    /** @var ZoneSpaceData[] $zones */
    public array $zones;

    public \DateTime $date;
}
