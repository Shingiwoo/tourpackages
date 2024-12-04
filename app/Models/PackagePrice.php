<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagePrice extends Model
{
    protected $fillable = ['package_id', 'price_data'];

    public function package()
    {
        return $this->belongsTo(PackageOneDay::class);
    }
}
