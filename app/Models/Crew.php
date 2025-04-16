<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Crew extends Model
{
    use HasFactory;

    protected $fillable = ['min_participants', 'max_participants', 'num_crew'];

}
