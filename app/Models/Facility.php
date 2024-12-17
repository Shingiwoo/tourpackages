<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'regency_id', 'price', 'type', 'max_user'];

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
}
