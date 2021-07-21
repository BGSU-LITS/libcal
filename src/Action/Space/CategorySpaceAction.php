<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitAvailability;
use Lits\LibCal\Action\TraitCache;
use Lits\LibCal\Action\TraitDetails;
use Lits\LibCal\Action\TraitIdMultiple;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\CategorySpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/** Action to get information about space/seat categories in your system. */
final class CategorySpaceAction extends Action
{
    use TraitAvailability;
    use TraitCache;
    use TraitDetails;
    use TraitIdMultiple;

    /**
     * Send request to the LibCal API.
     *
     * @return CategorySpaceData[] List of response data.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): array
    {
        $uri = '/' . Client::VERSION . '/space/category';
        $uri = $this->addId($uri);
        $uri = $this->addAvailability($uri);
        $uri = $this->addDetails($uri);

        /** @var CategorySpaceData[] $result */
        $result = $this->memoize(
            $uri,
            fn (string $uri) => CategorySpaceData::fromJsonAsArray(
                $this->client->get($uri)
            )
        );

        return $result;
    }
}
