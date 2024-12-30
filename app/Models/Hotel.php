<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [ 'regency_id', 'name', 'type', 'price', 'extrabed_price', 'status'];


    public static function getByRegency($regencyId)
    {
        return self::where('regency_id', $regencyId)->get();
    }
    /**
     * Relasi dengan regency (kabupaten/kota)
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id', 'id');
    }
}

