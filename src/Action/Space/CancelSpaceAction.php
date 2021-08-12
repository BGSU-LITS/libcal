<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitIdMultipleString;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\Cancel\ResponseCancelSpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/** Action to cancel a space/seat booking. */
final class CancelSpaceAction extends Action
{
    use TraitIdMultipleString;

    /**
     * Send request to the LibCal API.
     *
     * @return ResponseCancelSpaceData[] Response data.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): array
    {
        $uri = '/' . Client::VERSION . '/space/cancel';
        $uri = $this->addId($uri);

        return ResponseCancelSpaceData::fromJsonAsArray(
            $this->client->post($uri)
        );
    }
}
