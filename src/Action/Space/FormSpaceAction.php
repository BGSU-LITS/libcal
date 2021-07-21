<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitCache;
use Lits\LibCal\Action\TraitIdMultiple;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\FormSpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/** Action to get details of a space/seat booking form. */
final class FormSpaceAction extends Action
{
    use TraitCache;
    use TraitIdMultiple;

    /**
     * Send request to the LibCal API.
     *
     * @return FormSpaceData[] List of response data.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): array
    {
        $uri = '/' . Client::VERSION . '/space/form';
        $uri = $this->addId($uri);

        /** @var FormSpaceData[] $result */
        $result = $this->memoize(
            $uri,
            fn (string $uri) => FormSpaceData::fromJsonAsArray(
                $this->client->get($uri)
            )
        );

        return $result;
    }
}
