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
        return $this->hasMany(Booking::class, 'booking_list_id', 'id');
    }


    public function agen()
    {
        return $this->belongsTo(User::class, 'agen_id', 'id');
    }

    public function packageOneDay()
    {
        return $this->belongsTo(PackageOneDay::class, 'package_id', 'id');
    }

    public function packageTwoDay()
    {
        return $this->belongsTo(PackageTwoDay::class, 'package_id', 'id');
    }

    public function packageThreeDay()
    {
        return $this->belongsTo(PackageThreeDay::class, 'package_id', 'id');
    }

    public function packageFourDay()
    {
        return $this->belongsTo(PackageFourDay::class, 'package_id', 'id');
    }

    public function customPackage()
    {
        return $this->belongsTo(Custom::class, 'package_id', 'id');
    }

    public function rentService()
    {
        return $this->belongsTo(Facility::class, 'package_id', 'id');
    }
}


