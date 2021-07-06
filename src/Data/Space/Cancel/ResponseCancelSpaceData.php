<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space\Cancel;

use Lits\LibCal\Data;

final class ResponseCancelSpaceData extends Data
{
    public string $booking_id;
    public bool $cancelled;
    public ?string $error = null;
}
