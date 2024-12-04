<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageOneDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'agen_id',
        'name',
        'regency_id',
        'participants',
        'vehicle',
        'price_per_person',
        'total_price',
    ];

    /**
     * Relasi dengan regency (kabupaten/kota)
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    /**
     * Relasi dengan destinations (melalui pivot)
     */
    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'package_destinations');
    }
}
