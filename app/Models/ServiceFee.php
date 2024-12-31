<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceFee extends Model
{
    use HasFactory;

    protected $fillable = ['duration', 'mark'];

    public function scopeForDuration(Builder $query, string $duration): Builder
    {
        return $query->where('duration', $duration);
    }
}
