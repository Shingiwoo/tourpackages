<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Journal;
use App\Models\BookingCost;

class FixBookingIdInJournalEntries extends Command
{
    protected $signature = 'fix:booking-journal-entries';
    protected $description = 'Hapus semua journal duplikat berdasarkan reference_id dan perbaiki booking_id';

    public function handle()
    {
        // Ambil semua reference_id yang memiliki duplikat
        $duplicateReferenceIds = Journal::select('reference_id')
            ->where('reference_type', 'booking_cost')
            ->groupBy('reference_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('reference_id');

        // Hapus semua duplikat kecuali journal terbaru (berdasarkan ID tertinggi)
        foreach ($duplicateReferenceIds as $referenceId) {
            $latestJournal = Journal::where('reference_type', 'booking_cost')
                ->where('reference_id', $referenceId)
                ->orderByDesc('id')
                ->first();

            if ($latestJournal) {
                Journal::where('reference_type', 'booking_cost')
                    ->where('reference_id', $referenceId)
                    ->where('id', '!=', $latestJournal->id)
                    ->delete();
            }
        }

        // Perbaiki booking_id di journal entries
        $journals = Journal::where('reference_type', 'booking_cost')->get();
        foreach ($journals as $journal) {
            $bookingCost = BookingCost::find($journal->reference_id);
            if ($bookingCost) {
                // Update booking_id di semua entries journal ini
                $journal->entries()->update(['booking_id' => $bookingCost->booking_id]);
            }
        }

        $this->info('Semua journal duplikat berhasil dihapus dan booking_id telah diperbaiki.');
    }
}
