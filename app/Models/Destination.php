<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'regency_id',
        'name',
        'price_wni',
        'price_wna',
        'price_type',
        'max_participants',
        'parking_city_car',
        'parking_mini_bus',
        'parking_bus',
    ];

    /**
     * Relasi dengan regencies (kabupaten/kota)
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_destinations');
    }
}

