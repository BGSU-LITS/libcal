<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitCache;
use Lits\LibCal\Action\TraitIdSingle;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\ZoneSpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/** Action to get details of a zone in your system. */
final class ZoneSpaceAction extends Action
{
    use TraitCache;
    use TraitIdSingle;

    /**
     * Send request to the LibCal API.
     *
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): ZoneSpaceData
    {
        $uri = '/api/' . Client::VERSION . '/space/zone';
        $uri = $this->addId($uri);

        /** @var ZoneSpaceData $result */
        $result = $this->memoize(
            $uri,
            fn (string $uri) => ZoneSpaceData::fromJson(
                $this->client->get($uri)
            )
        );

        return $result;
    }
}
