<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

use Lits\LibCal\Action;
use Lits\LibCal\Action\Space\BookingSpaceAction;
use Lits\LibCal\Action\Space\BookingsSpaceAction;
use Lits\LibCal\Action\Space\CancelSpaceAction;
use Lits\LibCal\Action\Space\CategoriesSpaceAction;
use Lits\LibCal\Action\Space\CategorySpaceAction;
use Lits\LibCal\Action\Space\FormSpaceAction;
use Lits\LibCal\Action\Space\ItemSpaceAction;
use Lits\LibCal\Action\Space\ItemsSpaceAction;
use Lits\LibCal\Action\Space\LocationsSpaceAction;
use Lits\LibCal\Action\Space\NicknameSpaceAction;
use Lits\LibCal\Action\Space\QuestionSpaceAction;
use Lits\LibCal\Action\Space\ReserveSpaceAction;
use Lits\LibCal\Action\Space\SeatSpaceAction;
use Lits\LibCal\Action\Space\SeatsSpaceAction;
use Lits\LibCal\Action\Space\UtilizationSpaceAction;
use Lits\LibCal\Action\Space\ZoneSpaceAction;
use Lits\LibCal\Action\Space\ZonesSpaceAction;
use Lits\LibCal\Data\Space\Reserve\PayloadReserveSpaceData;

/** Spaces/Seats actions to perform on the LibCal API. */
final class SpaceAction extends Action
{
    /**
     * Get information about specific bookings in your system.
     *
     * @param string|string[] $id A booking id or list of booking ids.
     * @return BookingSpaceAction Action to be sent.
     */
    public function booking($id): BookingSpaceAction
    {
        return new BookingSpaceAction($this->client, $id);
    }

    /**
     * Get a list of bookings in your system.
     *
     * @return BookingsSpaceAction Action to be sent.
     */
    public function bookings(): BookingsSpaceAction
    {
        return new BookingsSpaceAction($this->client);
    }

    /**
     * Cancel a spaces/seats booking.
     *
     * @param string|string[] $id A booking id or list of booking ids to
     *  cancel.
     * @return CancelSpaceAction Action to be sent.
     */
    public function cancel($id): CancelSpaceAction
    {
        return new CancelSpaceAction($this->client, $id);
    }

    /**
     * Get a list of space/seat categories for locations in your system.
     *
     * @param int|int[] $id A location id or list of location ids to retrieve.
     * @return CategoriesSpaceAction Action to be sent.
     */
    public function categories($id): CategoriesSpaceAction
    {
        return new CategoriesSpaceAction($this->client, $id);
    }

    /**
     * Get information about space/seat categories in your system.
     *
     * @param int|int[] $id A category id or list of category ids to retrieve.
     * @return CategorySpaceAction Action to be sent.
     */
    public function category($id): CategorySpaceAction
    {
        return new CategorySpaceAction($this->client, $id);
    }

    /**
     * Get the details of a space/seat booking form.
     *
     * @param int|int[] $id A form id or list of form ids to retrieve.
     * @return FormSpaceAction Action to be sent.
     */
    public function form($id): FormSpaceAction
    {
        return new FormSpaceAction($this->client, $id);
    }

    /**
     * Get information and availability details of an item in your system.
     *
     * @param int|int[] $id An item id or list of item ids to retrieve.
     * @return ItemSpaceAction Action to be sent.
     */
    public function item($id): ItemSpaceAction
    {
        return new ItemSpaceAction($this->client, $id);
    }

    /**
     * Get information and availability details of items in your system.
     *
     * @param int $id The location id to retrieve.
     * @return ItemsSpaceAction Action to be sent.
     */
    public function items(int $id): ItemsSpaceAction
    {
        return new ItemsSpaceAction($this->client, $id);
    }

    /**
     * Get a list of public and private space/seat locations from your system.
     *
     * @return LocationsSpaceAction Action to be sent.
     */
    public function locations(): LocationsSpaceAction
    {
        return new LocationsSpaceAction($this->client);
    }

    /**
     * Get spaces/seats confirmed bookings, returning the Public Nicknames for
     * a given date.
     *
     * @param int|int[] $id A category id or list of category ids to retrieve.
     * @return NicknameSpaceAction Action to be sent.
     */
    public function nickname($id): NicknameSpaceAction
    {
        return new NicknameSpaceAction($this->client, $id);
    }

    /**
     * Get the details of space/seat booking form questions.
     *
     * @param int|int[] $id A question id or list of question ids to retrieve.
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
     * Get information and availability details of a seat in your system.
     *
     * @param int $id The seat id to retrieve.
     * @return SeatSpaceAction Action to be sent.
     */
    public function seat(int $id): SeatSpaceAction
    {
        return new SeatSpaceAction($this->client, $id);
    }

    /**
     * Get information and availability details of seats in your system.
     *
     * @param int $id The location id to retrieve.
     * @return SeatsSpaceAction Action to be sent.
     */
    public function seats(int $id): SeatsSpaceAction
    {
        return new SeatsSpaceAction($this->client, $id);
    }

    /**
     * Get current spaces utilization and occupancy data in your system.
     *
     * @param int $id The location id to retrieve.
     * @return UtilizationSpaceAction Action to be sent.
     */
    public function utilization(int $id): UtilizationSpaceAction
    {
        return new UtilizationSpaceAction($this->client, $id);
    }

    /**
     * Get details of a zone in your system.
     *
     * @param int $id The zone id to retrieve.
     * @return ZoneSpaceAction Action to be sent.
     */
    public function zone(int $id): ZoneSpaceAction
    {
        return new ZoneSpaceAction($this->client, $id);
    }

    /**
     * List details of zones in your system.
     *
     * @param int $id The location id to retrieve zones for.
     * @return ZonesSpaceAction Action to be sent.
     */
    public function zones(int $id): ZonesSpaceAction
    {
        return new ZonesSpaceAction($this->client, $id);
    }
}
