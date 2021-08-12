<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitCache;
use Lits\LibCal\Action\TraitDate;
use Lits\LibCal\Action\TraitFormAnswers;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\BookingSpaceData;
use Lits\LibCal\Exception\ActionException;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/** Action to get a list of bookings in your system. */
final class BookingsSpaceAction extends Action
{
    use TraitCache;
    use TraitDate;
    use TraitFormAnswers;

    /** @var int[]|null Item ids to only show bookings for those spaces. */
    public ?array $eid = null;

    /** @var int[]|null Seat ids to only show bookings for those seats. */
    public ?array $seat_id = null;

    /**
     * Category ids to only show bookings for those categories.
     *
     * @var int[]|null
     */
    public ?array $cid = null;

    /** Location id to only show bookings for that location. */
    public ?int $lid = null;

    /** Email address to only show bookings made by that patron. */
    public ?string $email = null;

    /**
     * The number of days into the future to retrieve bookings from, starting
     * from "date" parameter. Range 0-365.
     */
    public ?int $days = null;

    /**
     * How many bookings to return per page. If your request returns the same
     * number of results as this "limit" value, you should request another
     * "page" of results. Range 1-500.
     */
    public ?int $limit = null;

    /**
     * Which page of results to return. 1 for first page, 2 for second page,
     * etc.
     */
    public ?int $page = null;

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
        $uri = '/' . Client::VERSION . '/space/bookings';
        $uri = self::addQuery($uri, 'eid', self::list($this->eid));
        $uri = self::addQuery($uri, 'seat_id', self::list($this->seat_id));
        $uri = self::addQuery($uri, 'cid', self::list($this->cid));
        $uri = self::addQuery($uri, 'lid', $this->lid);
        $uri = self::addQuery($uri, 'email', $this->email);
        $uri = $this->addDate($uri);
        $uri = self::addQuery($uri, 'days', $this->days);
        $uri = self::addQuery($uri, 'limit', $this->limit);
        $uri = self::addQuery($uri, 'page', $this->page);
        $uri = $this->addFormAnswers($uri);

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
     * Set an item id or list of item ids here to only show bookings for those
     * spaces.
     *
     * @param int|int[]|null $eid Value to set.
     * @return self A reference to this object for method chaining.
     */
    public function setEid($eid): self
    {
        if (!\is_null($eid) && !\is_array($eid)) {
            $eid = [$eid];
        }

        $this->eid = $eid;

        return $this;
    }

    /**
     * Set a seat id or list of seat ids here to only show bookings for those
     * seats.
     *
     * @param int|int[]|null $seat_id Value to set.
     * @return self A reference to this object for method chaining.
     */
    public function setSeatId($seat_id): self
    {
        if (!\is_null($seat_id) && !\is_array($seat_id)) {
            $seat_id = [$seat_id];
        }

        $this->seat_id = $seat_id;

        return $this;
    }

    /**
     * Set a category id or list of category ids here to only show bookings
     * for those categories.
     *
     * @param int|int[]|null $cid Value to set.
     * @return self A reference to this object for method chaining.
     */
    public function setCid($cid): self
    {
        if (!\is_null($cid) && !\is_array($cid)) {
            $cid = [$cid];
        }

        $this->cid = $cid;

        return $this;
    }

    /**
     * Set a location id here to only show bookings for that location.
     *
     * @param int $lid Value to set.
     * @return self A reference to this object for method chaining.
     */
    public function setLid(int $lid): self
    {
        $this->lid = $lid;

        return $this;
    }

    /**
     * Set an email address to only show bookings made by that patron.
     *
     * @param string $email Value to set.
     * @return self A reference to this object for method chaining.
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the number of days into the future to retrieve bookings from,
     * starting from "date" parameter. Range 0-365.
     *
     * @param int $days Value to set.
     * @return self A reference to this object for method chaining.
     * @throws ActionException If an invalid value is specified.
     */
    public function setDays(int $days = 0): self
    {
        $days_min = 0;
        $days_max = 365;

        if ($days < $days_min || $days > $days_max) {
            throw new ActionException(
                'Days must be within ' . (string) $days_min . ' and ' .
                (string) $days_max
            );
        }

        $this->days = $days;

        return $this;
    }

    /**
     * Set how many bookings to return per page. If your request returns the
     * same number of results as this "limit" value, you should request
     * another "page" of results. Range 1-100.
     *
     * @param int $limit Value to set.
     * @return self A reference to this object for method chaining.
     * @throws ActionException If an invalid value is specified.
     */
    public function setLimit(int $limit = 20): self
    {
        $limit_min = 1;
        $limit_max = 365;

        if ($limit < $limit_min || $limit > $limit_max) {
            throw new ActionException(
                'Limit must be within ' . (string) $limit_min . ' and ' .
                (string) $limit_max
            );
        }

        $this->limit = $limit;

        return $this;
    }

    /**
     * Set which page of results to return. 1 for first page, 2 for second
     * page, etc.
     *
     * @param int $page Value to set.
     * @return self A reference to this object for method chaining.
     * @throws ActionException If an invalid value is specified.
     */
    public function setPage(int $page = 1): self
    {
        $page_min = 1;

        if ($page < $page_min) {
            throw new ActionException(
                'Page must be ' . (string) $page_min . ' or higher'
            );
        }

        $this->page = $page;

        return $this;
    }

    /**
     * Convert an array of integers to a string separated by commas.
     *
     * @param int[]|null $array The array to be listed.
     * @return string|null The list as a string separated by commas.
     */
    private static function list(?array $array): ?string
    {
        if (\is_array($array)) {
            return \implode(',', $array);
        }

        return null;
    }
}
