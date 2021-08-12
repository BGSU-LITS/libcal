<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitAvailability;
use Lits\LibCal\Action\TraitCache;
use Lits\LibCal\Action\TraitIdSingle;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\SeatSpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/**
 * Action to get information and availability details of a seat in your
 * system.
 */
final class SeatSpaceAction extends Action
{
    use TraitAvailability;
    use TraitCache;
    use TraitIdSingle;

    /**
     * Send request to the LibCal API.
     *
     * @return SeatSpaceData List of response data.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): SeatSpaceData
    {
        $uri = '/api/' . Client::VERSION . '/space/seat';
        $uri = $this->addId($uri);
        $uri = $this->addAvailability($uri);

        /** @var SeatSpaceData $result */
        $result = $this->memoize(
            $uri,
            fn (string $uri) => SeatSpaceData::fromJson(
                $this->client->get($uri)
            )
        );

        return $result;
    }

    /**
     * Do not allow for the string "next" as part of the availability.
     *
     * @return bool Always false.
     */
    protected function availabilityAllowNext(): bool
    {
        return false;
    }
}
