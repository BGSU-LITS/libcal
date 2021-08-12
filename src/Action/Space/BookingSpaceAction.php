<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitCache;
use Lits\LibCal\Action\TraitFormAnswers;
use Lits\LibCal\Action\TraitIdMultipleString;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\BookingSpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/** Action to get information about specific bookings in your system. */
final class BookingSpaceAction extends Action
{
    use TraitCache;
    use TraitFormAnswers;
    use TraitIdMultipleString;

    /**
     * Send request to the LibCal API.
     *
     * @return BookingSpaceData[] List of response data.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): array
    {
        $uri = '/' . Client::VERSION . '/space/booking';
        $uri = $this->addId($uri);
        $uri = $this->addFormAnswers($uri);

        /** @var BookingSpaceData[] $result */
        $result = $this->memoize(
            $uri,
            fn (string $uri) => BookingSpaceData::fromJsonAsArray(
                $this->client->get($uri)
            )
        );

        return $result;
    }
}
