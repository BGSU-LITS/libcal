<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitData;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\Reserve\ResponseReserveSpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/** Action to book spaces/seats in your system. */
final class ReserveSpaceAction extends Action
{
    use TraitData;

    /**
     * Send request to the LibCal API.
     *
     * @return ResponseReserveSpaceData Response data.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): ResponseReserveSpaceData
    {
        $uri = '/' . Client::VERSION . '/space/reserve';

        return ResponseReserveSpaceData::fromJson(
            $this->postWithData($uri)
        );
    }
}
