<?php

namespace App\Services\Accounting;

use App\Models\BookingCost;
use App\Models\Journal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingCostService
{
    protected $journalBuilder;

    public function __construct(JournalBuilderService $journalBuilder)
    {
        $this->journalBuilder = $journalBuilder;
    }

    public function save(BookingCost $bookingCost, array $data): void
    {
        DB::transaction(function () use ($bookingCost, $data) {
            try {
                $bookingCost->fill($data);
                $bookingCost->save();

                $this->cleanupOldJournals($bookingCost);
                $this->journalBuilder->createBookingCostJournal($bookingCost);

                Log::info('BookingCost berhasil disimpan', ['id' => $bookingCost->id]);
            } catch (\Exception $e) {
                Log::error('Gagal menyimpan BookingCost', [
                    'error' => $e->getMessage(),
                    'bookingCost' => $bookingCost->toArray(),
                    'data' => $data
                ]);
                throw $e;
            }
        });
    }

    public function delete(BookingCost $bookingCost): void
    {
        DB::transaction(function () use ($bookingCost) {
            try {
                $this->cleanupOldJournals($bookingCost);
                $bookingCost->delete();

                Log::info('BookingCost berhasil dihapus', ['id' => $bookingCost->id]);
            } catch (\Exception $e) {
                Log::error('Gagal menghapus BookingCost', [
                    'error' => $e->getMessage(),
                    'bookingCost' => $bookingCost->toArray()
                ]);
                throw $e;
            }
        });
    }

    protected function cleanupOldJournals(BookingCost $bookingCost): void
    {
        Journal::where('reference_type', BookingCost::class)
            ->where('reference_id', $bookingCost->id)
            ->delete();
    }
}
