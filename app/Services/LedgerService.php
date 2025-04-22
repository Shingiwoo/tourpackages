<?php

// ========== LEDGER SERVICE: Buku Besar ========== //

namespace App\Services;

use App\Models\JournalEntry;
use App\Models\Account;

class LedgerService
{
    public function generate(array $filters = [])
    {
        $query = JournalEntry::with(['journal', 'account']);

        if (!empty($filters['start_date'])) {
            $query->whereHas('journal', function ($q) use ($filters) {
                $q->whereDate('date', '>=', $filters['start_date']);
            });
        }

        if (!empty($filters['end_date'])) {
            $query->whereHas('journal', function ($q) use ($filters) {
                $q->whereDate('date', '<=', $filters['end_date']);
            });
        }

        if (!empty($filters['account_id'])) {
            $query->where('account_id', $filters['account_id']);
        }

        $entries = $query->orderBy('journal_id')->get()->groupBy('account_id');

        $ledgers = [];

        foreach ($entries as $accountId => $entryGroup) {
            $account = Account::find($accountId);
            $debitTotal = $entryGroup->sum('debit');
            $creditTotal = $entryGroup->sum('credit');
            $saldo = $debitTotal - $creditTotal;

            $ledgers[] = [
                'account' => $account,
                'entries' => $entryGroup,
                'debit' => $debitTotal,
                'credit' => $creditTotal,
                'saldo' => $saldo,
            ];
        }

        return $ledgers;
    }
}
