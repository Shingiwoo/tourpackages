<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Relasi dengan regencies (kabupaten/kota)
     */
    public function regencies()
    {
        return $this->hasMany(Regency::class);
    }
}

