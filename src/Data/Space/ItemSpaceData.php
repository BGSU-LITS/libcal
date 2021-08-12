<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;

final class ItemSpaceData extends Data
{
    public int $id;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $image = null;
    public ?int $capacity = null;
    public ?int $formid = null;
    public ?bool $isBookableAsWhole = null;
    public ?bool $isAccessible = null;
    public ?bool $isPowered = null;
    public ?bool $isEventLocation = null;
    public ?int $zoneId = null;
    public ?string $zoneName = null;
    public ?int $groupId = null;
    public ?string $groupName = null;

    /** @var AvailabilitySpaceData[] $availability */
    public ?array $availability = null;
}
