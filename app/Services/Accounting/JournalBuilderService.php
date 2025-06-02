<?php

namespace App\Services\Accounting;

use App\Models\Account;
use App\Models\Journal;
use App\Models\BookingCost;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;

class JournalBuilderService
{
    /**
     * Membuat jurnal baru
     *
     * @param array $data
     * @return Journal
     */
    public function create(array $data): void
    {
        DB::transaction(function () use ($data) {
            $this->validateJournalData($data);

            $journal = Journal::create([
                'reference_type' => $data['reference_type'],
                'reference_id' => $data['reference_id'],
                'journal_type' => $data['journal_type'] ?? null,
                'date' => $data['date'],
                'description' => $data['description'],
            ]);

            foreach ($data['entries'] as $entry) {
                $this->createJournalEntry($journal, $entry);
            }
        });
    }

    /**
     * Membuat dan memperbarui jurnal
     */
    public function createOrUpdate(array $data): void
    {
        DB::transaction(function () use ($data) {
            $this->validateJournalData($data);

            // Cari atau buat jurnal
            $journal = Journal::firstOrNew([
                'reference_type' => $data['reference_type'],
                'reference_id' => $data['reference_id']
            ]);

            // Update atribut
            $journal->fill([
                'journal_type' => $data['journal_type'] ?? null,
                'date' => $data['date'],
                'description' => $data['description']
            ])->save();

            // Hapus entri lama
            $journal->entries()->delete();

            // Buat entri baru
            foreach ($data['entries'] as $entry) {
                $this->createJournalEntry($journal, $entry);
            }
        });
    }

    /**
     * Membuat jurnal khusus untuk BookingCost
     */
    public function createBookingCostJournal(BookingCost $bookingCost): void
    {
        $this->createOrUpdate([
            'date' => $bookingCost->date,
            'description' => $bookingCost->description,
            'reference_type' => BookingCost::class,
            'reference_id' => $bookingCost->id,
            'journal_type' => 'cost',
            'entries' => [
                [
                    'account_code' => $bookingCost->account->code,
                    'booking_id' => $bookingCost->booking_id,
                    'debit' => $bookingCost->amount,
                    'credit' => 0,
                ],
                [
                    'account_code' => '1-001', // Kas/Bank
                    'booking_id' => $bookingCost->booking_id,
                    'debit' => 0,
                    'credit' => $bookingCost->amount,
                ],
            ],
        ]);
    }

    /**
     * Memperbarui jurnal expense
     */
    public function updateExpenseJournal(BookingCost $bookingCost, $oldBookingId = null): void
    {
        $this->createOrUpdate([
            'date' => $bookingCost->date,
            'description' => $bookingCost->description,
            'reference_type' => BookingCost::class,
            'reference_id' => $bookingCost->id,
            'journal_type' => 'cost',
            'entries' => [
                [
                    'account_code' => $bookingCost->account->code,
                    'booking_id' => $bookingCost->booking_id,
                    'debit' => $bookingCost->amount,
                    'credit' => 0,
                ],
                [
                    'account_code' => '1-001', // Kas/Bank
                    'booking_id' => $bookingCost->booking_id,
                    'debit' => 0,
                    'credit' => $bookingCost->amount,
                ],
            ],
        ]);
    }

    /**
     * Validasi data jurnal
     */
    protected function validateJournalData(array $data): void
    {
        if (!isset($data['entries']) || !is_array($data['entries']) || count($data['entries']) < 2) {
            throw new \InvalidArgumentException('Journal must have at least two entries');
        }

        $requiredFields = ['date', 'description', 'reference_type', 'reference_id'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new \InvalidArgumentException("Field {$field} is required");
            }
        }
    }

    /**
     * Membuat entri jurnal
     */
    protected function createJournalEntry(Journal $journal, array $entryData): JournalEntry
    {
        $account = Account::findOrFail($entryData['account_id']);

        return JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $account->id,
            'booking_id' => $entryData['booking_id'] ?? null,
            'debit' => $entryData['debit'] ?? 0,
            'credit' => $entryData['credit'] ?? 0,
        ]);
    }
}
