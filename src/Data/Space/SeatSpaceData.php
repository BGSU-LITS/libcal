<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;

final class SeatSpaceData extends Data
{
    public int $id;
    public string $name;
    public string $description;
    public bool $isAccessible;
    public bool $isPowered;
    public string $image;
    public string $status;

    /** @var AvailabilitySpaceData[] $availability */
    public ?array $availability = null;
}
