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
        'ket',
        'status',
    ];

    /**
     * Relasi dengan regencies (kabupaten/kota)
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id', 'id');
    }

    public static function getByRegency($regencyId)
    {
        return self::where('regency_id', $regencyId)->get();
    }

    public function packages()
    {
        return $this->belongsToMany(PackageOneDay::class, 'package_destinations');
    }

    public function packagesTwoday()
    {
        return $this->belongsToMany(PackageTwoDay::class, 'package_destinations');
    }
}

