<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

trait TraitPowered
{
    /** A flag to only return powered spaces. */
    public ?bool $powered = null;

    /**
     * Set to only return powered spaces.
     *
     * @param bool $powered Value to set, enabling by default.
     * @return self A reference to this object for method chaining.
     */
    final public function setPowered(bool $powered = true): self
    {
        $this->powered = $powered;

        return $this;
    }

    /**
     * Add powered query param to a URI.
     *
     * @param string $uri The URI to add query param to.
     * @return string The URI with added query param.
     */
    final protected function addPowered(string $uri): string
    {
        return self::addQuery($uri, 'powered', $this->powered);
    }
}
