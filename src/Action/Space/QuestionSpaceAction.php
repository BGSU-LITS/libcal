<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitIdMultiple;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\QuestionSpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/** Action to get details of space/seat booking form questions. */
final class QuestionSpaceAction extends Action
{
    use TraitIdMultiple;

    /**
     * Send request to the LibCal API.
     *
     * @return QuestionSpaceData[] List of response data.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): array
    {
        $uri = '/' . Client::VERSION . '/space/question';
        $uri = $this->addId($uri);

        return QuestionSpaceData::fromJsonAsArray($this->client->get($uri));
    }
}
