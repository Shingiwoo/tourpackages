<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageThreePrice extends Model
{

    protected $fillable = ['package_three_day_id', 'price_data'];

    // Decode JSON from price_data
    public function getPricesAttribute()
    {
        return json_decode($this->price_data, true);
    }

    public function package()
    {
        return $this->belongsTo(PackageThreeDay::class);
    }
}
