<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingCost extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'account_id', 'description', 'amount'];

    protected $table = 'booking_costs';

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
