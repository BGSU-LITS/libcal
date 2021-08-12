<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;

final class CategorySpaceBookingSpaceData extends Data
{
    public string $nickname;
    public \DateTime $start;
    public \DateTime $end;
    public \DateTime $created;
    public string $booking_id;
}
