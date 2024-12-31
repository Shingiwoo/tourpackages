<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = ['price','type', 'duration', 'num_meals', 'regency_id'];

    /**
     * Relasi dengan regencies (kabupaten/kota)
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    public function scopeForDuration(Builder $query, string $duration): Builder
    {
        return $query->where('duration', $duration);
    }

}
