<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitCache;
use Lits\LibCal\Action\TraitCategoryId;
use Lits\LibCal\Action\TraitIdSingle;
use Lits\LibCal\Action\TraitZoneId;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\UtilizationSpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/**
 * Action to get current spaces utilization and occupancy data in your system.
 */
final class UtilizationSpaceAction extends Action
{
    use TraitCache;
    use TraitCategoryId;
    use TraitIdSingle;
    use TraitZoneId;

    /**
     * Send request to the LibCal API.
     *
     * @return UtilizationSpaceData Response data.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): UtilizationSpaceData
    {
        $uri = '/api/' . Client::VERSION . '/space/utilization';
        $uri = $this->addId($uri);
        $uri = $this->addCategoryId($uri);
        $uri = $this->addZoneId($uri);

        /** @var UtilizationSpaceData $result */
        $result = $this->memoize(
            $uri,
            fn (string $uri) => UtilizationSpaceData::fromJson(
                $this->client->get($uri)
            )
        );

        return $result;
    }
}
