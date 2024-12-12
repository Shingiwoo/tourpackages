<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageFourPrice extends Model
{
    protected $fillable = ['package_four_day_id', 'price_data'];

    // Decode JSON from price_data
    public function getPricesAttribute()
    {
        return json_decode($this->price_data, true);
    }

    public function package()
    {
        return $this->belongsTo(PackageFourDay::class);
    }
}
