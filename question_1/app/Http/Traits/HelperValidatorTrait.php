<?php

namespace App\Http\Traits;

use Carbon\Carbon;

trait HelperValidatorTrait
{
        /**
     * Check whether correct time interval for
     * work only allowed 2 and 4 hours
     *
     * @param string $start Start time
     * @param string $end   End time
     *
     * @return boolean
     */
    protected function isValidInterval($start, $end): bool
    {
        $start = Carbon::createFromFormat('H:i', $start);
        $end = Carbon::createFromFormat('H:i', $end);
        $diff = $end->diffInMinutes($start);

        return $diff == 2 * 60 || $diff == 4 * 60;
    }

    /**
     * Check whether given interval exists or not
     *
     * @param Collection $bookings Booking collections for a day
     * @param string     $start    Start time
     * @param string     $end      End time
     *
     * @return boolean
     */
    protected function isAvailableIntervalExists($bookings, $start, $end): bool
    {
        $start = Carbon::createFromFormat('H:i', $start);
        $end = Carbon::createFromFormat('H:i', $end);

        foreach ($bookings as $booking) {
            if (
                ($start->lt($booking->start) && $end->gt($booking->start)) ||
                ($start->lt($booking->end) && $end->gt($booking->end)) ||
                ($start->gte($booking->start) && $end->lte($booking->end))
            ) {
                return false;
            }
        }

        return true;
    }
}
