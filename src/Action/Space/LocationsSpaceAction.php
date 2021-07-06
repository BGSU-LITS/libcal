<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitAdminOnly;
use Lits\LibCal\Action\TraitDetails;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\LocationSpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/** Action to list public and private space/seat locations from your system. */
final class LocationsSpaceAction extends Action
{
    use TraitAdminOnly;
    use TraitDetails;

    /**
     * Send request to the LibCal API.
     *
     * @return LocationSpaceData[] List of response data.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): array
    {
        $uri = '/' . Client::VERSION . '/space/locations';
        $uri = $this->addAdminOnly($uri);
        $uri = $this->addDetails($uri);

        return LocationSpaceData::fromJsonAsArray($this->client->get($uri));
    }
}
