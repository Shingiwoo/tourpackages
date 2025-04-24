<?php

namespace App\Services;

use App\Models\Journal;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;

class JournalService
{
    public function createExpenseJournal($bookingCost)
    {
        DB::transaction(function () use ($bookingCost) {
            $journal = Journal::create([
                'date' => now()->toDateString(),
                'description' => $bookingCost->description,
                'reference_type' => 'booking_cost',
                'reference_id' => $bookingCost->id,
            ]);

            // Debit ke akun biaya (akun beban)
            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $bookingCost->account_id,
                'debit' => $bookingCost->amount,
                'credit' => 0,
            ]);

            // Kredit ke kas/bank
            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => 1, // ID akun Kas/Bank (1-001)
                'debit' => 0,
                'credit' => $bookingCost->amount,
            ]);
        });
    }

    public function updateExpenseJournal($bookingCost)
    {
        DB::transaction(function () use ($bookingCost) {
            // Cari jurnal yang refer ke booking_cost ini
            $journal = Journal::where('reference_type', 'booking_cost')
                ->where('reference_id', $bookingCost->id)
                ->first();

            if (!$journal) {
                // Kalau gak ketemu, buat baru aja
                return $this->createExpenseJournal($bookingCost);
            }

            $journal->update([
                'description' => $bookingCost->description,
                'date' => now()->toDateString(),
            ]);

            // Hapus entry lama
            $journal->entries()->delete();

            // Masukkan ulang entry baru
            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $bookingCost->account_id,
                'debit' => $bookingCost->amount,
                'credit' => 0,
            ]);

            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => 1, // akun Kas/Bank
                'debit' => 0,
                'credit' => $bookingCost->amount,
            ]);
        });
    }

}
