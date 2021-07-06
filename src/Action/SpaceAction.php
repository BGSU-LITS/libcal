<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

use Lits\LibCal\Action;
use Lits\LibCal\Action\Space\BookingSpaceAction;
use Lits\LibCal\Action\Space\CancelSpaceAction;
use Lits\LibCal\Action\Space\CategoriesSpaceAction;
use Lits\LibCal\Action\Space\CategorySpaceAction;
use Lits\LibCal\Action\Space\FormSpaceAction;
use Lits\LibCal\Action\Space\ItemSpaceAction;
use Lits\LibCal\Action\Space\ItemsSpaceAction;
use Lits\LibCal\Action\Space\LocationsSpaceAction;
use Lits\LibCal\Action\Space\QuestionSpaceAction;
use Lits\LibCal\Action\Space\ReserveSpaceAction;
use Lits\LibCal\Action\Space\ZoneSpaceAction;
use Lits\LibCal\Action\Space\ZonesSpaceAction;
use Lits\LibCal\Data\Space\Reserve\PayloadReserveSpaceData;

/** Spaces/Seats actions to perform on the LibCal API. */
final class SpaceAction extends Action
{
    /**
     * Get information about specific bookings in your system.
     *
     * @param string|string[] $id The booking ID or IDs to retrieve.
     * @return BookingSpaceAction Action to be sent.
     */
    public function booking($id): BookingSpaceAction
    {
        return new BookingSpaceAction($this->client, $id);
    }

    /**
     * Cancel a spaces/seats booking.
     *
     * @param string|string[] $id The booking ID or IDs to cancel.
     * @return CancelSpaceAction Action to be sent.
     */
    public function cancel($id): CancelSpaceAction
    {
        return new CancelSpaceAction($this->client, $id);
    }

    /**
     * List space/seat categories for locations in your system.
     *
     * @param int|int[] $id The location ID or IDs to retrieve.
     * @return CategoriesSpaceAction Action to be sent.
     */
    public function categories($id): CategoriesSpaceAction
    {
        return new CategoriesSpaceAction($this->client, $id);
    }

    /**
     * Get information about space/seat categories in your system.
     *
     * @param int|int[] $id The category ID or IDs to retrieve.
     * @return CategorySpaceAction Action to be sent.
     */
    public function category($id): CategorySpaceAction
    {
        return new CategorySpaceAction($this->client, $id);
    }

    /**
     * Get details of a space/seat booking form.
     *
     * @param int|int[] $id The form ID or IDs to retrieve.
     * @return FormSpaceAction Action to be sent.
     */
    public function form($id): FormSpaceAction
    {
        return new FormSpaceAction($this->client, $id);
    }

    /**
     * Get information and availability of a space in your system.
     *
     * @param int|int[] $id The space ID or IDs to retrieve.
     * @return ItemSpaceAction Action to be sent.
     */
    public function item($id): ItemSpaceAction
    {
        return new ItemSpaceAction($this->client, $id);
    }

    /**
     * Get information and availability of spaces in your system.
     *
     * @param int $id Location ID to retrieve spaces for.
     * @return ItemsSpaceAction Action to be sent.
     */
    public function items(int $id): ItemsSpaceAction
    {
        return new ItemsSpaceAction($this->client, $id);
    }

    /**
     * List public and private space/seat locations from your system.
     *
     * @return LocationsSpaceAction Action to be sent.
     */
    public function locations(): LocationsSpaceAction
    {
        return new LocationsSpaceAction($this->client);
    }

    /**
     * Get the details of space/seat booking form questions.
     *
     * @param int|int[] $id The question ID or IDs to retrieve.
     * @return QuestionSpaceAction Action to be sent.
     */
    public function question($id): QuestionSpaceAction
    {
        return new QuestionSpaceAction($this->client, $id);
    }

    /**
     * Book spaces/seats in your system.
     *
     * @param PayloadReserveSpaceData $data The data to post.
     * @return ReserveSpaceAction Action to be sent.
     */
    public function reserve(PayloadReserveSpaceData $data): ReserveSpaceAction
    {
        return new ReserveSpaceAction($this->client, $data);
    }

    /**
     * Get details of a zone in your system.
     *
     * @param int $id The zone ID to retrieve details for.
     * @return ZoneSpaceAction Action to be sent.
     */
    public function zone(int $id): ZoneSpaceAction
    {
        return new ZoneSpaceAction($this->client, $id);
    }

    /**
     * List details of zones in your system.
     *
     * @param int $id The location ID to retrieve zones for.
     * @return ZonesSpaceAction Action to be sent.
     */
    public function zones(int $id): ZonesSpaceAction
    {
        return new ZonesSpaceAction($this->client, $id);
    }
}
