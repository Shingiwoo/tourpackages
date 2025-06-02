<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_list_id', 'code_booking', 'start_date', 'end_date', 'name', 'package_name',
        'start_trip', 'end_trip', 'type', 'total_user', 'price_person', 'total_price',
        'down_paymet', 'remaining_costs', 'status', 'note'
    ];

    public function bookingList()
    {
        return $this->belongsTo(BookingList::class, 'booking_list_id', 'id');
    }

    public function costs()
    {
        return $this->hasMany(BookingCost::class);
    }

    public function journals()
    {
        return $this->hasManyThrough(
            Journal::class,
            JournalEntry::class,
            'booking_id',     // FK di JournalEntry
            'id',             // FK di Journal (primary key)
            'id',             // localKey di Booking
            'journal_id'      // FK Journal di JournalEntry
        )->distinct();
    }

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }


}

