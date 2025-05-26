<?php

namespace App\Services\Accounting;

use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\SupplierInvoice;
use App\Services\Accounting\AccountMappingService;
use Illuminate\Support\Facades\DB;

class SupplierInvoiceJournalService
{
    public static function create(SupplierInvoice $invoice)
    {
        DB::transaction(function () use ($invoice) {
            $accountHutang = AccountMappingService::get('hutang_usaha');
            $accountBiaya = AccountMappingService::get('expense'); // Atau custom tergantung jenis

            $journal = Journal::create([
                'date' => $invoice->date,
                'description' => "Invoice dari Supplier: {$invoice->supplier_name}",
                'reference_type' => SupplierInvoice::class,
                'reference_id' => $invoice->id,
                'journal_type' => 'supplier_invoice',
            ]);

            // DEBIT biaya
            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $accountBiaya->id,
                'debit' => $invoice->amount,
                'credit' => 0,
            ]);

            // KREDIT hutang usaha
            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $accountHutang->id,
                'debit' => 0,
                'credit' => $invoice->amount,
            ]);
        });
    }
}
