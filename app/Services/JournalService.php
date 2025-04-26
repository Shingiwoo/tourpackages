<?php

namespace App\Services;

use App\Models\Account;
use App\Models\BookingCost;
use App\Models\Journal;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JournalService
{
    public function createExpenseJournal(BookingCost $bookingCost)
    {
        DB::transaction(function () use ($bookingCost) {
            // Cek apakah jurnal dengan reference_id yang sama sudah ada
            $existingJournal = Journal::where('reference_type', 'booking_cost')
                ->where('reference_id', $bookingCost->id)
                ->first();

            if ($existingJournal) {
                Log::warning('Duplikasi pembuatan jurnal dicegah untuk BookingCost #' . $bookingCost->id);
                return; // Hentikan pembuatan jika sudah ada
            }

            // Buat journal baru
            $journal = Journal::create([
                'date' => $bookingCost->date,
                'description' => $bookingCost->description,
                'reference_type' => 'booking_cost',
                'reference_id' => $bookingCost->id,
            ]);

            // Buat journal entries
            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $bookingCost->account_id,
                'booking_id' => $bookingCost->booking_id,
                'debit' => $bookingCost->amount,
                'credit' => 0,
            ]);

            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $this->getCashAccountId(),
                'booking_id' => $bookingCost->booking_id,
                'debit' => 0,
                'credit' => $bookingCost->amount,
            ]);
        });
    }

    public function updateExpenseJournal(BookingCost $expense, $oldBookingId = null)
    {
        DB::transaction(function () use ($expense, $oldBookingId) {
            // Hapus jurnal entry lama jika booking_id berubah
            if ($oldBookingId && $oldBookingId != $expense->booking_id) {
                JournalEntry::where('booking_id', $oldBookingId)
                    ->where('account_id', $expense->account_id)
                    ->where('debit', $expense->amount)
                    ->delete();
            }

            // Cari jurnal yang sudah ada berdasarkan reference_type dan reference_id
            $journal = Journal::where('reference_type', 'booking_cost')
                ->where('reference_id', $expense->id)
                ->first();

            if ($journal) {
                // Update date dan description jika jurnal sudah ada
                $journal->update([
                    'description' => $expense->description,
                    'date' => $expense->date,
                ]);

                // Hapus semua entry sebelumnya dan buat ulang
                $journal->entries()->delete();
                $journal->entries()->createMany([
                    [
                        'account_id' => $expense->account_id,
                        'booking_id' => $expense->booking_id,
                        'debit' => $expense->amount,
                        'credit' => 0,
                    ],
                    [
                        'account_id' => $this->getCashAccountId(),
                        'booking_id' => $expense->booking_id,
                        'debit' => 0,
                        'credit' => $expense->amount,
                    ],
                ]);

                Log::info('Jurnal berhasil diperbarui untuk BookingCost #' . $expense->id);

            } else {
                // Kalau belum ada, buat baru
                $journal = Journal::create([
                    'description' => $expense->description,
                    'date' => $expense->date,
                    'reference_type' => 'booking_cost',
                    'reference_id' => $expense->id,
                ]);
                $journal->entries()->createMany([
                    [
                        'account_id' => $expense->account_id,
                        'booking_id' => $expense->booking_id,
                        'debit' => $expense->amount,
                        'credit' => 0,
                    ],
                    [
                        'account_id' => $this->getCashAccountId(),
                        'booking_id' => $expense->booking_id,
                        'debit' => 0,
                        'credit' => $expense->amount,
                    ],
                ]);
                Log::info('Jurnal baru dibuat untuk BookingCost #' . $expense->id);
            }
        });
    }


    protected function getCashAccountId()
    {
        // Misalnya pakai kode akun 1-001 atau cari by name
        return Account::where('code', '1-001')->value('id');
    }



}
