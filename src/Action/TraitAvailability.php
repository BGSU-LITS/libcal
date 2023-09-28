<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

use Lits\LibCal\Exception\ActionException;

trait TraitAvailability
{
    /**
     * Either a single date, or a comma separated list of 2 dates (a start and
     * end date, max range of 31 days).
     *
     * Dates must be formatted YYYY-MM-DD. The keyword "next" can be used to
     * return availability for the next date that this resource is available.
     * The keyword "next_only" can be used to return availability for the next
     * date that this resource is available. This will only return the first
     * timeslot that the resource is available for. Also note that this method
     * only searches up to 10 days ahead of the current date.
     */
    public ?string $availability = null;

    /**
     * Set either a single date, or list of 2 dates (a start and end date).
     *
     * @param string|string[]|\DateTimeInterface[]|null $availability Value to
     *  set. The keyword "next" can be used to
     *  return availability for the next date that this item is available.
     * @return self A reference to this object for method chaining.
     * @throws ActionException If an invalid value is specified.
     */
    final public function setAvailability($availability = null): self
    {
        if (!\is_null($availability)) {
            if (!\is_array($availability)) {
                $availability = [$availability];
            }

            $filter = [];

            foreach ($availability as $key => $value) {
                if ($value instanceof \DateTimeInterface) {
                    $value = $value->format('Y-m-d');
                }

                $filter[$key] = $value;
            }

            $regexp = '/^(\d{4}-\d{2}-\d{2}(,\d{4}-\d{2}-\d{2})?|next_only' .
                ($this->availabilityAllowNext() ? '|next' : '') . ')$/';

            $availability = \filter_var(
                \implode(',', $filter),
                \FILTER_VALIDATE_REGEXP,
                ['options' => ['regexp' => $regexp]]
            );

            if ($availability === false) {
                throw new ActionException('Invalid availability specified');
            }
        }

        $this->availability = $availability;

        return $this;
    }

    /**
     * Add availability query param to a URI.
     *
     * @param string $uri The URI to add query param to.
     * @return string The URI with added query param.
     */
    final protected function addAvailability(string $uri): string
    {
        return self::addQuery($uri, 'availability', $this->availability);
    }

    /**
     * Allow for the string "next" as part of the availability.
     *
     * @return bool Always true. Overwrite this method in classes using this
     * trait to disallow "next" by returning false.
     */
    final protected function availabilityAllowNext(): bool
    {
        return true;
    }
}
