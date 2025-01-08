<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageOneDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'agen_id',
        'name_package',
        'status',
        'regency_id',
        'facility_id',
        'information'
    ];

    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'package_destinations', 'package_id', 'destination_id');
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'facilities_one_days', 'package_id', 'facility_id');
    }

    public function prices()
    {
        return $this->hasOne(PackagePrice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'agen_id', 'id');
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id', 'id');
    }

    public static function countByAgen($agenId)
    {
        return static::where('agen_id', $agenId)->count();
    }
}
