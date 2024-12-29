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
        return $this->hasMany(Destination::class, 'regency_id', 'id');
    }

    public function packages()
    {
        return $this->hasMany(PackageOneDay::class);
    }


    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'regency_id', 'id');
    }


    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }


    public function fasilities()
    {
        return $this->hasMany(Facility::class, 'regency_id', 'id');
    }

    public function meals()
    {
        return $this->hasMany(Meal::class, 'regency_id', 'id');
    }
}

