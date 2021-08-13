<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;
use Lits\LibCal\Data\TraitQuestions;

final class BookingSpaceData extends Data
{
    use TraitQuestions;

    public string $bookId;
    public int $eid;
    public int $cid;
    public int $lid;
    public ?int $seat_id = null;
    public \DateTime $fromDate;
    public \DateTime $toDate;
    public \DateTime $created;
    public string $firstName;
    public string $lastName;
    public string $email;
    public string $account;
    public string $status;
    public string $location_name;
    public string $category_name;
    public string $item_name;
    public ?string $seat_name = null;
    public ?string $nickname = null;
    public ?string $check_in_code = null;
}
