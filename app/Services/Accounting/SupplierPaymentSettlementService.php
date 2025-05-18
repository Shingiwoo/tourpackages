<?php

namespace App\Services;

use App\Models\SupplierInvoice;
use App\Models\SupplierPayment;
use App\Models\SupplierPaymentHistory;
use App\Models\SupplierDeposit;
use Illuminate\Support\Facades\DB;

class SupplierPaymentSettlementService
{
    public static function settle(SupplierInvoice $invoice, float $paymentAmount, ?string $notes = null)
    {
        DB::transaction(function () use ($invoice, $paymentAmount, $notes) {
            // 1. Cari DP tersedia
            $availableDeposits = SupplierDeposit::where('supplier_id', $invoice->supplier_id)
                ->where('remaining_amount', '>', 0)
                ->orderBy('payment_date')
                ->get();

            $amountToSettle = $paymentAmount;
            foreach ($availableDeposits as $deposit) {
                if ($amountToSettle <= 0) {
                    break;
                }

                $useAmount = min($deposit->remaining_amount, $amountToSettle);

                // Kurangi DP
                $deposit->remaining_amount -= $useAmount;
                $deposit->save();

                // Buat histori pembayaran
                SupplierPaymentHistory::create([
                    'supplier_payment_id' => null, // Ini kalau mau dipisah, atau isi saat full payment
                    'supplier_invoice_id' => $invoice->id,
                    'amount' => $useAmount,
                    'notes' => 'Settled from deposit',
                ]);

                $amountToSettle -= $useAmount;
            }

            // 2. Kalau masih ada sisa, catat sebagai payment baru
            if ($amountToSettle > 0) {
                $payment = SupplierPayment::create([
                    'supplier_id' => $invoice->supplier_id,
                    'supplier_invoice_id' => $invoice->id,
                    'payment_date' => now(),
                    'amount' => $amountToSettle,
                    'payment_method' => 'cash', // default
                    'notes' => $notes ?? 'Cash payment',
                ]);

                SupplierPaymentHistory::create([
                    'supplier_payment_id' => $payment->id,
                    'supplier_invoice_id' => $invoice->id,
                    'amount' => $amountToSettle,
                    'notes' => $notes ?? 'Cash payment',
                ]);
            }

            // 3. Update invoice status kalau sudah lunas
            $invoice->refreshTotalPaid();
        });
    }
}
