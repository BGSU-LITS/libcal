<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitAvailability;
use Lits\LibCal\Action\TraitCache;
use Lits\LibCal\Action\TraitIdSingle;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\ItemSpaceData;
use Lits\LibCal\Exception\ActionException;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/** Action to get information and availability of spaces in your system. */
final class ItemsSpaceAction extends Action
{
    use TraitAvailability;
    use TraitCache;
    use TraitIdSingle;

    public const PAGE_SIZE_MIN = 1;
    public const PAGE_SIZE_MAX = 100;

    /** A category ID to only show spaces from this category. */
    public ?int $categoryId = null;

    /** A zone ID to only show details for this zone. */
    public ?int $zoneId = null;

    /** A flag to only return accessible spaces. */
    public ?bool $accessibleOnly = null;

    /** A flag to only return Bookable As Whole spaces. */
    public ?bool $bookable = null;

    /** A flag to only return powered spaces. */
    public ?bool $powered = null;

    /**
     * For results pagination, this sets which page to retrieve (starting at 0
     * for the first page). Defaults to 0.
     */
    public ?int $pageIndex = null;

    /**
     * For results pagination, this sets how many results per page to retrieve.
     * Defaults to 20, may be from 1-100.
     */
    public ?int $pageSize = null;

    /**
     * Send request to the LibCal API.
     *
     * @return ItemSpaceData[] List of response data.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function send(): array
    {
        $uri = '/api/' . Client::VERSION . '/space/items';
        $uri = $this->addId($uri);
        $uri = $this->addAvailability($uri);
        $uri = self::addQuery($uri, 'categoryId', $this->categoryId);
        $uri = self::addQuery($uri, 'zoneId', $this->zoneId);
        $uri = self::addQuery($uri, 'accessibleOnly', $this->accessibleOnly);
        $uri = self::addQuery($uri, 'bookable', $this->bookable);
        $uri = self::addQuery($uri, 'powered', $this->powered);
        $uri = self::addQuery($uri, 'pageIndex', $this->pageIndex);
        $uri = self::addQuery($uri, 'pageSize', $this->pageSize);

        /** @var ItemSpaceData[] $result */
        $result = $this->memoize(
            $uri,
            fn (string $uri) => ItemSpaceData::fromJsonAsArray(
                $this->client->get($uri)
            )
        );

        return $result;
    }

    /**
     * Set a category ID to only show spaces from this category.
     *
     * @param int $categoryId Value to set.
     * @return self A reference to this object for method chaining.
     */
    public function setCategoryId(int $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Set a zone ID to only show details for this zone.
     *
     * @param int $zoneId Value to set.
     * @return self A reference to this object for method chaining.
     */
    public function setZoneId(int $zoneId): self
    {
        $this->zoneId = $zoneId;

        return $this;
    }

    /**
     * Set to only return accessible spaces.
     *
     * @param bool $accessibleOnly Value to set, enabling by default.
     * @return self A reference to this object for method chaining.
     */
    public function setAccessibleOnly(bool $accessibleOnly = true): self
    {
        $this->accessibleOnly = $accessibleOnly;

        return $this;
    }

    /**
     * Set to only return Bookable As Whole spaces.
     *
     * @param bool $bookable Value to set, enabling by default.
     * @return self A reference to this object for method chaining.
     */
    public function setBookable(bool $bookable = true): self
    {
        $this->bookable = $bookable;

        return $this;
    }

    /**
     * Set to only return powered spaces.
     *
     * @param bool $powered Value to set, enabling by default.
     * @return self A reference to this object for method chaining.
     */
    public function setPowered(bool $powered = true): self
    {
        $this->powered = $powered;

        return $this;
    }

    /**
     * Set which page to retrieve (starting at 0 for the first page).
     *
     * @param int $pageIndex Value to set.
     * @return self A reference to this object for method chaining.
     */
    public function setPageIndex(int $pageIndex): self
    {
        $this->pageIndex = $pageIndex;

        return $this;
    }

    /**
     * Set how many results per page to retrieve.
     *
     * @param int $pageSize Value to set.
     * @return self A reference to this object for method chaining.
     * @throws ActionException If the page size is not between 1 and 100.
     */
    public function setPageSize(int $pageSize): self
    {
        if (
            $pageSize < self::PAGE_SIZE_MIN ||
            $pageSize > self::PAGE_SIZE_MAX
        ) {
            throw new ActionException('Page size must be between 1 and 100');
        }

        $this->pageSize = $pageSize;

        return $this;
    }
}
