<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitCache;
use Lits\LibCal\Action\TraitIdMultipleString;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\BookingSpaceData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/** Action to get information about specific bookings in your system. */
final class BookingSpaceAction extends Action
{
    use TraitCache;
    use TraitIdMultipleString;

    /** Include custom form answers in your request. */
    public ?bool $formAnswers = null;

    /**
     * Send request to the LibCal API.
     *
     * @return BookingSpaceData[] List of response data.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): array
    {
        $uri = '/' . Client::VERSION . '/space/booking';
        $uri = $this->addId($uri);
        $uri = self::addQuery($uri, 'formAnswers', $this->formAnswers);

        /** @var BookingSpaceData[] $result */
        $result = $this->memoize(
            $uri,
            fn (string $uri) => BookingSpaceData::fromJsonAsArray(
                $this->client->get($uri)
            )
        );

        return $result;
    }

    /**
     * Set to include custom form answers in your request.
     *
     * @param bool $formAnswers Value to set, enabling by default.
     * @return self A reference to this object for method chaining.
     */
    public function setFormAnswers(bool $formAnswers = true): self
    {
        $this->formAnswers = $formAnswers;

        return $this;
    }
}
