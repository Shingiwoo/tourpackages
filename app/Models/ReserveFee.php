<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReserveFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'duration',
        'min_user',
        'max_user',
    ];

    public function scopeForDuration(Builder $query, string $duration): Builder
    {
        return $query->where('duration', $duration);
    }
}
