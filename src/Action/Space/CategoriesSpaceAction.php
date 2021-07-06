<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitAdminOnly;
use Lits\LibCal\Action\TraitIdMultiple;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\CategoriesSpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/** Action to list space/seat categories for locations in your system. */
final class CategoriesSpaceAction extends Action
{
    use TraitAdminOnly;
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
        $uri = '/' . Client::VERSION . '/space/categories';
        $uri = $this->addId($uri);
        $uri = $this->addAdminOnly($uri);

        return CategoriesSpaceData::fromJsonAsArray($this->client->get($uri));
    }
}
