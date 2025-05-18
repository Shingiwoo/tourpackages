<?php

namespace App\Services\Accounting;

use App\Models\Journal;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;
use App\Services\Accounting\AccountMappingService;

class DepositSupplierJournalService
{
    public static function create($supplierDeposit)
    {
        DB::transaction(function () use ($supplierDeposit) {
            $accountCash = AccountMappingService::get('cash');
            $accountSupplierDeposit = AccountMappingService::get('supplier_deposit');

            $journal = Journal::create([
                'date' => $supplierDeposit->date,
                'description' => "Deposit to Supplier {$supplierDeposit->supplier_name}",
                'reference_type' => get_class($supplierDeposit),
                'reference_id' => $supplierDeposit->id,
                'journal_type' => 'supplier_deposit',
            ]);

            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $accountSupplierDeposit->id,
                'debit' => $supplierDeposit->amount,
                'credit' => 0,
            ]);

            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $accountCash->id,
                'debit' => 0,
                'credit' => $supplierDeposit->amount,
            ]);
        });
    }
}
