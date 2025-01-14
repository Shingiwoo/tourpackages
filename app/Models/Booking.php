<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [ 'code_booking', 'name_package', 'type', 'price', 'extrabed_price', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'agen_id', 'id');
    }
}
