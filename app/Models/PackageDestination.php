<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageDestination extends Model
{
    use HasFactory;

    protected $fillable = ['package_id', 'destination_id'];


    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'package_destinations');
    }
}
