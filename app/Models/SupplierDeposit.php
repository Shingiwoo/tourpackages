<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierDeposit extends Model
{
    protected $table = 'supplier_deposits';

    protected $fillable = [
        'supplier_name',
        'booking_id',
        'date',
        'amount',
        'remaining_amount',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function supplier() { return $this->belongsTo(Supplier::class); }

    public function histories(){return $this->hasMany(SupplierPaymentHistory::class, 'supplier_deposit_id'); }

}
