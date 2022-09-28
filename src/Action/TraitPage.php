<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

use Lits\LibCal\Exception\ActionException;

trait TraitPage
{
    /**
     * For results pagination, this sets which page to retrieve (starting at 0
     * for the first page). Default: 0.
     */
    public ?int $pageIndex = null;

    /**
     * For results pagination, this sets how many results per page to retrieve.
     * Default: 20 Range: 1-100.
     */
    public ?int $pageSize = null;

    /**
     * Add pageIndex query param to a URI.
     *
     * @param string $uri The URI to add query param to.
     * @return string The URI with added query param.
     */
    final protected function addPageIndex(string $uri): string
    {
        return self::addQuery($uri, 'pageIndex', $this->pageIndex);
    }

    /**
     * Add pageSize query param to a URI.
     *
     * @param string $uri The URI to add query param to.
     * @return string The URI with added query param.
     */
    final protected function addPageSize(string $uri): string
    {
        return self::addQuery($uri, 'pageSize', $this->pageSize);
    }

    /**
     * For results pagination, set which page to retrieve (starting at 0 for
     * the first page).
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
     * For results pagination, set how many results per page to retrieve.
     *
     * @param int $pageSize Value to set. Range 1-100.
     * @return self A reference to this object for method chaining.
     * @throws ActionException If an invalid value is specified.
     */
    public function setPageSize(int $pageSize): self
    {
        $pageSizeMin = 1;
        $pageSizeMax = 100;

        if ($pageSize < $pageSizeMin || $pageSize > $pageSizeMax) {
            throw new ActionException(
                'Page size must be within ' . (string) $pageSizeMin . ' and ' .
                (string) $pageSizeMax
            );
        }

        $this->pageSize = $pageSize;

        return $this;
    }
}
