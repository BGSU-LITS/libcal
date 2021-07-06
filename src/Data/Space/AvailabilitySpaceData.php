<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;

final class AvailabilitySpaceData extends Data
{
    public \DateTime $from;
    public \DateTime $to;
}
