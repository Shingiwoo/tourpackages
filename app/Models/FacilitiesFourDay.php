<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacilitiesFourDay extends Model
{

    use HasFactory;

    protected $fillable = ['package_id', 'facility_id'];
}
