<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;

final class CategorySpaceSpaceData extends Data
{
    public int $id;
    public string $name;

    /** @var CategorySpaceBookingSpaceData[] $bookings */
    public ?array $bookings = null;
}
