<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitIdSingle;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\ZoneSpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/** Action to list details of zones in your system. */
final class ZonesSpaceAction extends Action
{
    use TraitIdSingle;

    /**
     * Send request to the LibCal API.
     *
     * @return ZoneSpaceData[] List of response data.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): array
    {
        $uri = '/api/' . Client::VERSION . '/space/zones';
        $uri = $this->addId($uri);

        return ZoneSpaceData::fromJsonAsArray($this->client->get($uri));
    }
}
