<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [ 'regency_id', 'name', 'type', 'capacity', 'price', 'extrabed_price', 'status'];

    /**
     * Relasi dengan regency (kabupaten/kota)
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id', 'id');
    }

    // Scope untuk memfilter hotel berdasarkan regency_id
    public function scopeByRegency($query, $regencyId)
    {
        return $query->where('regency_id', $regencyId);
    }

    // Scope untuk hanya hotel aktif
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}

