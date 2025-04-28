<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'description', 'date', 'reference_type', 'reference_id', 'journal_type'
    ];

    public function entries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Helper accessor: Total Debit
    public function getTotalDebitAttribute()
    {
        return $this->entries->sum('debit');
    }

    // Helper accessor: Total Credit
    public function getTotalCreditAttribute()
    {
        return $this->entries->sum('credit');
    }

    // Journal.php
    public function scopeFromBooking($query, $bookingId)
    {
        return $query->whereHas('entries', function ($q) use ($bookingId) {
            $q->where('booking_id', $bookingId);
        });
    }

}
