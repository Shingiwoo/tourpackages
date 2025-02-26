<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_list_id', 'code_booking', 'start_date', 'end_date',
        'start_trip', 'end_trip', 'type', 'total_user', 'price_person', 'total_price',
        'down_paymet', 'remaining_costs', 'status', 'note'
    ];

    public function bookingList()
    {
        return $this->belongsTo(BookingList::class, 'booking_list_id', 'id');
    }
}

