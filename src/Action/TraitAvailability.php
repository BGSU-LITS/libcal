<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

use Lits\LibCal\Exception\ActionException;

trait TraitAvailability
{
    /**
     * A single date or a start and end date to retrieve availability.
     *
     * Dates must be formatted YYYY-MM-DD. Two dates must be separated by a
     * comma. The string "next" can be used to return availability for the next
     * date that this item is available.
     */
    public ?string $availability = null;

    /**
     * Set to a single date or a start and end date to retrieve availability.
     *
     * @param string|string[]|\DateTimeInterface[]|null $availability Value to
     *  set. The string "next" can be used to return availability for the next
     *  date that this item is available.
     * @return self A reference to this object for method chaining.
     * @throws ActionException If an invalid availability is specified.
     */
    final public function setAvailability($availability): self
    {
        if (!\is_null($availability)) {
            if (!\is_array($availability)) {
                $availability = [$availability];
            }

            foreach ($availability as $key => $value) {
                if ($value instanceof \DateTimeInterface) {
                    $availability[$key] = $value->format('Y-m-d');
                }
            }

            $availability = \filter_var(
                \implode(',', $availability),
                \FILTER_VALIDATE_REGEXP,
                ['options' => ['regexp' =>
                    '/(^\d{4}-\d{2}-\d{2}(,\d{4}-\d{2}-\d{2})?)|next$/']]
            );

            if ($availability === false) {
                throw new ActionException('Invalid availability specified');
            }
        }

        $this->availability = $availability;

        return $this;
    }

    /**
     * Add availability query to a URI.
     *
     * @param string $uri The URI to add an availability query to.
     * @return string The URI with added availability query.
     */
    final protected function addAvailability(string $uri): string
    {
        return self::addQuery($uri, 'availability', $this->availability);
    }
}
