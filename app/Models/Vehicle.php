<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['regency_id', 'name', 'type', 'capacity_min', 'capacity_max','price', 'status'];

    /**
     * Relasi dengan regency (kabupaten/kota)
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id', 'id');
    }

    public static function getByRegency($regencyId)
    {
        return self::where('regency_id', $regencyId)->get();
    }
}

