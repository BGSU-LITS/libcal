<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitAvailability;
use Lits\LibCal\Action\TraitCache;
use Lits\LibCal\Action\TraitIdMultiple;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\ItemSpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/** Action to get information and availability of a space in your system. */
final class ItemSpaceAction extends Action
{
    use TraitAvailability;
    use TraitCache;
    use TraitIdMultiple;

    /**
     * Send request to the LibCal API.
     *
     * @return ItemSpaceData[] List of response data.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): array
    {
        $uri = '/' . Client::VERSION . '/space/item';
        $uri = $this->addId($uri);
        $uri = $this->addAvailability($uri);

        /** @var ItemSpaceData[] $result */
        $result = $this->memoize(
            $uri,
            fn (string $uri) => ItemSpaceData::fromJsonAsArray(
                $this->client->get($uri)
            )
        );

        return $result;
    }
}
