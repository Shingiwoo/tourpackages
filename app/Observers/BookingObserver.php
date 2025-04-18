<?php

namespace App\Observers;

use App\Models\Booking;
use App\Services\BookingAccountingService;

class BookingObserver
{
    public function updated(Booking $booking)
    {
        if ($booking->isDirty('status')) {
            app(BookingAccountingService::class)->handle($booking);
        }
    }
}
