<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

trait TraitDetails
{
    /** Include additional details. */
    public ?bool $details = null;

    /**
     * Set to include additional details.
     *
     * @param bool $details Value to set, enabling by default.
     * @return self A reference to this object for method chaining.
     */
    final public function setDetails(bool $details = true): self
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Add details param to a URI.
     *
     * @param string $uri The URI to add details query to.
     * @return string The URI with added details query.
     */
    final protected function addDetails(string $uri): string
    {
        return self::addQuery($uri, 'details', $this->details);
    }
}
