<?php

namespace App\Services\Accounting;

use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\SupplierPayment;
use Illuminate\Support\Facades\DB;

class SupplierPaymentJournalService
{
    public static function create(SupplierPayment $payment)
    {
        DB::transaction(function () use ($payment) {
            $accountCash = AccountMappingService::get('cash');
            $accountPayable = AccountMappingService::get('accounts_payable'); // bisa diubah sesuai mapping

            $journal = Journal::create([
                'date' => $payment->payment_date,
                'description' => "Payment to Supplier: {$payment->supplier->name}",
                'reference_type' => SupplierPayment::class,
                'reference_id' => $payment->id,
                'journal_type' => 'supplier_payment',
            ]);

            // Kredit Kas (uang keluar)
            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $accountCash->id,
                'debit' => 0,
                'credit' => $payment->amount,
            ]);

            // Debit Hutang (karena bayar ke supplier)
            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $accountPayable->id,
                'debit' => $payment->amount,
                'credit' => 0,
            ]);
        });
    }
}
