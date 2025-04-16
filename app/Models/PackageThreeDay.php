<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageThreeDay extends Model
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
        return $this->belongsToMany(Destination::class, 'package_three_destinations', 'package_id', 'destination_id');
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'facilities_three_days', 'package_id', 'facility_id');
    }

    public function prices()
    {
        return $this->hasOne(PackageThreePrice::class);
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

    protected $table = 'package_three_days';

    // Accessor untuk 'type'
    public function getTypeAttribute()
    {
        return 'threeday';
    }
}
