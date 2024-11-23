<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'regency_id'];

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
