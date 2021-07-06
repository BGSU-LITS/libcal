<?php

declare(strict_types=1);

namespace Lits\LibCal\Data;

use Lits\LibCal\Data;

final class TokenData extends Data
{
    public string $access_token;
    public int $expires_in;
    public string $token_type;
    public string $scope;
}
