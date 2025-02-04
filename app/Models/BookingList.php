<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingList extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'agen_id', 'package_id'];


    public function bookings()
    {
        return $this->hasMany(Booking::class, 'booking_id', 'id');
    }

    public function agen()
    {
        return $this->belongsTo(User::class, 'agen_id', 'id');
    }
}

