<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;

final class LocationSpaceData extends Data
{
    public int $lid;
    public string $name;
    public bool $public;
    public ?int $formid = null;
    public ?string $terms = null;
    public ?bool $admin_only = null;
}
