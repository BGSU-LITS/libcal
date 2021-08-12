<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitCache;
use Lits\LibCal\Action\TraitDate;
use Lits\LibCal\Action\TraitIdMultiple;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\CategoriesSpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/**
 * Action to get spaces/seats confirmed bookings, returning the Public
 * Nicknames for a given date.
 */
final class NicknameSpaceAction extends Action
{
    use TraitCache;
    use TraitDate;
    use TraitIdMultiple;

    /**
     * Send request to the LibCal API.
     *
     * @return CategoriesSpaceData[] List of response data.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): array
    {
        $uri = '/' . Client::VERSION . '/space/nickname';
        $uri = $this->addId($uri);
        $uri = $this->addDate($uri);

        /** @var CategoriesSpaceData[] $result */
        $result = $this->memoize(
            $uri,
            fn (string $uri) => CategoriesSpaceData::fromJsonAsArray(
                $this->client->get($uri)
            )
        );

        return $result;
    }
}
