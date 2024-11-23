<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    use HasFactory;

    protected $fillable = ['province_id', 'name'];

    /**
     * Relasi dengan provinces (provinsi)
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Relasi dengan destinations (destinasi wisata)
     */
    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }

    /**
     * Relasi dengan packages (Paket Wisata)
     */
    public function packages()
    {
        return $this->hasMany(Package::class);
    }


    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }


    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }
}

