<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

use Lits\LibCal\Exception\ActionException;

trait TraitDate
{
    /** The date to retrieve bookings. Dates in the past are ignored. */
    public ?string $date = null;

    /**
     * Set the date to retrieve bookings. Dates in the past are ignored.
     *
     * @param string|\DateTimeInterface|null $date Value to set.
     * @return self A reference to this object for method chaining.
     * @throws ActionException If an invalid value is specified.
     */
    final public function setDate($date): self
    {
        if (!\is_null($date)) {
            if ($date instanceof \DateTimeInterface) {
                $date = $date->format('Y-m-d');
            }

            $date = \filter_var(
                $date,
                \FILTER_VALIDATE_REGEXP,
                ['options' => ['regexp' => '/^\d{4}-\d{2}-\d{2}$/']]
            );

            if ($date === false) {
                throw new ActionException('Invalid date specified');
            }
        }

        $this->date = $date;

        return $this;
    }

    /**
     * Add date query param to a URI.
     *
     * @param string $uri The URI to add query param to.
     * @return string The URI with added query param.
     */
    final protected function addDate(string $uri): string
    {
        return self::addQuery($uri, 'date', $this->date);
    }
}
