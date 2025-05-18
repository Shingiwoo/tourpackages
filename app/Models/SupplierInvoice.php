<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierInvoice extends Model
{
    protected $table = 'supplier_invoices';

    protected $fillable = [
        'supplier_name',
        'booking_id',
        'invoice_number',
        'date',
        'due_date',
        'amount',
        'description',
        'settled',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'settled' => 'boolean',
    ];

    public function refreshTotalPaid()
    {
        $totalPaid = $this->paymentHistories()->sum('amount');

        if ($totalPaid >= $this->total_amount) {
            $this->status = 'paid';
        } else {
            $this->status = 'unpaid';
        }

        $this->save();
    }

    public function paymentHistories()
    {
        return $this->hasMany(SupplierPaymentHistory::class);
    }
}
