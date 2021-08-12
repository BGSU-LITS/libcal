<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

trait TraitDetails
{
    /** Flag to indicate you want additional details. */
    public ?bool $details = null;

    /**
     * Set to indicate you want additional details.
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
     * Add details query param to a URI.
     *
     * @param string $uri The URI to add query param to.
     * @return string The URI with added query param.
     */
    final protected function addDetails(string $uri): string
    {
        return self::addQuery($uri, 'details', $this->details);
    }
}
