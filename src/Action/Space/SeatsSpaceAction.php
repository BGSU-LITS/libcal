<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitAccessibleOnly;
use Lits\LibCal\Action\TraitAvailability;
use Lits\LibCal\Action\TraitCache;
use Lits\LibCal\Action\TraitCategoryId;
use Lits\LibCal\Action\TraitIdSingle;
use Lits\LibCal\Action\TraitPage;
use Lits\LibCal\Action\TraitPowered;
use Lits\LibCal\Action\TraitZoneId;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\SeatSpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/**
 * Action to get information and availability details of seats in your system.
 */
final class SeatsSpaceAction extends Action
{
    use TraitAccessibleOnly;
    use TraitAvailability;
    use TraitCache;
    use TraitCategoryId;
    use TraitIdSingle;
    use TraitPage;
    use TraitPowered;
    use TraitZoneId;

    /**
     * Send request to the LibCal API.
     *
     * @return SeatSpaceData[] List of response data.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): array
    {
        $uri = '/api/' . Client::VERSION . '/space/seats';
        $uri = $this->addId($uri);
        $uri = $this->addAvailability($uri);
        $uri = $this->addCategoryId($uri);
        $uri = $this->addZoneId($uri);
        $uri = $this->addAccessibleOnly($uri);
        $uri = $this->addPowered($uri);
        $uri = $this->addPageIndex($uri);
        $uri = $this->addPageSize($uri);

        /** @var SeatSpaceData[] $result */
        $result = $this->memoize(
            $uri,
            fn (string $uri) => SeatSpaceData::fromJsonAsArray(
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
