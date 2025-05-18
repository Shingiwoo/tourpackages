<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierPayment extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supplier_id',
        'supplier_invoice_id',
        'booking_id',
        'payment_date',
        'amount',
        'payment_method',
        'notes',
    ];

    public function histories()
    {
        return $this->hasMany(SupplierPaymentHistory::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function invoice()
    {
        return $this->belongsTo(SupplierInvoice::class, 'supplier_invoice_id');
    }
    public function journal()
    {
        return $this->hasOne(Journal::class, 'reference_id')->where('reference_type', self::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function getFormattedDateAttribute()
    {
        return $this->date ? \Carbon\Carbon::parse($this->date)->format('d/m/Y') : null;
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2, '.', '');
    }

    public function getFormattedSupplierNameAttribute()
    {
        return $this->supplier_name ? ucwords($this->supplier_name) : null;
    }

}
