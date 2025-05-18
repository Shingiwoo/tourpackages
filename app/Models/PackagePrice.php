<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagePrice extends Model
{
    protected $fillable = ['package_one_day_id', 'price_data'];
    protected $appends = ['prices'];

    // Decode JSON from price_data
    public function getPricesAttribute()
    {
        try {
            return json_decode($this->price_data, true) ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function package()
    {
        return $this->belongsTo(PackageOneDay::class);
    }
}
