<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageTwoDestination extends Model
{

    use HasFactory;

    protected $fillable = ['package_id', 'destination_id'];
}
