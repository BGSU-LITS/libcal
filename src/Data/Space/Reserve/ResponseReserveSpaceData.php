<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space\Reserve;

use Lits\LibCal\Data;

final class ResponseReserveSpaceData extends Data
{
    public string $booking_id;
    public ?float $cost = null;
}
