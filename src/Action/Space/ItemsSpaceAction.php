<?php

declare(strict_types=1);

namespace Lits\LibCal\Action\Space;

use Lits\LibCal\Action;
use Lits\LibCal\Action\TraitAccessibleOnly;
use Lits\LibCal\Action\TraitAvailability;
use Lits\LibCal\Action\TraitCache;
use Lits\LibCal\Action\TraitCategoryId;
use Lits\LibCal\Action\TraitIdSingle;
use Lits\LibCal\Action\TraitPage;
use Lits\LibCal\Action\TraitPowered;
use Lits\LibCal\Action\TraitZoneId;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Space\ItemSpaceData;
use Lits\LibCal\Exception\ActionException;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

/**
 * Action to get information and availability details of spaces in your
 * system.
 */
final class ItemsSpaceAction extends Action
{
    use TraitAccessibleOnly;
    use TraitAvailability;
    use TraitCache;
    use TraitCategoryId;
    use TraitIdSingle;
    use TraitPage;
    use TraitPowered;
    use TraitZoneId;

    /**
     * Sets which items to be returned: "public", "private" or "admin_only".
     * Default: "public".
     */
    public ?string $visibility = null;

    /** A flag to only return Bookable As Whole spaces. */
    public ?bool $bookable = null;

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
        $uri = $this->addCategoryId($uri);
        $uri = $this->addZoneId($uri);
        $uri = $this->addAccessibleOnly($uri);
        $uri = self::addQuery($uri, 'bookable', $this->bookable);
        $uri = $this->addPowered($uri);
        $uri = $this->addAvailability($uri);
        $uri = $this->addPageIndex($uri);
        $uri = $this->addPageSize($uri);
        $uri = self::addQuery($uri, 'visibility', $this->visibility);

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
     * Sets which items to be returned.
     *
     * @param string $visibility Can be "public", "private" or "admin_only".
     *   If you provide a "categoryId" then this option is ignored and all
     *   items for the "categoryId" will be returned.
     *   If you provide "private" for this option then items from both public
     *   and private categories will be returned.
     *   If you provide "admin_only" for this option then all items for the
     *   location will be returned.
     * @return self A reference to this object for method chaining.
     * @throws ActionException If an invalid value is specified.
     */
    public function setVisibility(string $visibility = 'public'): self
    {
        $options = ['public', 'private', 'admin_only'];

        if (!\in_array($visibility, $options, true)) {
            throw new ActionException(
                'Visibility must be one of: ' . \implode(', ', $options)
            );
        }

        $this->visibility = $visibility;

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
}
