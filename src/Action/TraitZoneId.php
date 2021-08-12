<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

trait TraitZoneId
{
    /** A zone ID to only show details for this zone. */
    public ?int $zoneId = null;

    /**
     * Set a zone ID to only show details for this zone.
     *
     * @param int $zoneId Value to set.
     * @return self A reference to this object for method chaining.
     */
    final public function setZoneId(int $zoneId): self
    {
        $this->zoneId = $zoneId;

        return $this;
    }

    /**
     * Add zoneId param to a URI.
     *
     * @param string $uri The URI to add query param to.
     * @return string The URI with added query param.
     */
    final protected function addZoneId(string $uri): string
    {
        return self::addQuery($uri, 'zoneId', $this->zoneId);
    }
}
