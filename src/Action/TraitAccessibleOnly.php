<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

trait TraitAccessibleOnly
{
    /** A flag to only return accessible spaces. */
    public ?bool $accessibleOnly = null;

    /**
     * Add accessibleOnly query param to a URI.
     *
     * @param string $uri The URI to add query param to.
     * @return string The URI with added query param.
     */
    final protected function addAccessibleOnly(string $uri): string
    {
        return self::addQuery($uri, 'accessibleOnly', $this->accessibleOnly);
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
}
