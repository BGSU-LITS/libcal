<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space\Reserve;

use Lits\LibCal\Data;

final class BookingReserveSpaceData extends Data
{
    /** @required */
    public int $id;

    public ?int $seat_id = null;

    /** @required */
    public \DateTime $to;
}
