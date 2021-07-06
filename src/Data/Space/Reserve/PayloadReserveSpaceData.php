<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space\Reserve;

use Lits\LibCal\Data;
use Lits\LibCal\Data\TraitQuestions;
use Lits\LibCal\Exception\DataException;

final class PayloadReserveSpaceData extends Data
{
    use TraitQuestions;

    /** @required */
    public \DateTime $start;

    /** @required */
    public string $fname;

    /** @required */
    public string $lname;

    /** @required */
    public string $email;

    public ?string $nickname = null;
    public ?bool $adminbooking = null;
    public ?bool $test = null;

    /**
     * @var BookingReserveSpaceData[] $bookings
     * @required
     */
    public array $bookings;

    /**
     * Get properties as a JSON object.
     *
     * @return string JSON object.
     * @throws DataException
     */
    public function json(): string
    {
        $format = 'Y-m-d\TH:i:sO';

        $data = \array_filter((array) $this);
        $data['start'] = $this->start->format($format);
        $data['bookings'] = [];

        foreach ($this->bookings as $item => $booking) {
            $data['bookings'][$item] = \array_filter((array) $booking);
            $data['bookings'][$item]['to'] = $booking->to->format($format);
        }

        unset($data['questions']);

        foreach ($this->questions as $key => $value) {
            $data[$key] = $value;
        }

        foreach (\array_keys($data) as $key) {
            if ($key === 'bookings' || !\is_array($data[$key])) {
                continue;
            }

            $data[$key] = \implode(', ', $data[$key]);
        }

        try {
            return \json_encode($data, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new DataException(
                'JSON could not be encoded',
                0,
                $exception
            );
        }
    }
}
