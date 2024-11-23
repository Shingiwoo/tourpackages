<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'capacity', 'price', 'regency_id'];

    /**
     * Relasi dengan regency (kabupaten/kota)
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }
}

