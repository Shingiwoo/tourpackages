<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Journal;
use App\Models\BookingCost;

class FixBookingIdInJournalEntries extends Command
{
    protected $signature = 'fix:booking-journal-entries';
    protected $description = 'Hapus journal duplikat dan perbaiki booking_id';

    public function handle()
    {
        // Grupkan journal berdasarkan reference_id untuk cek duplikat
        $duplicateJournals = Journal::where('reference_type', 'booking_cost')
            ->groupBy('reference_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('reference_id');

        // Hapus journal duplikat (simpan yang paling baru)
        foreach ($duplicateJournals as $referenceId) {
            $latestJournal = Journal::where('reference_type', 'booking_cost')
                ->where('reference_id', $referenceId)
                ->latest()
                ->first();

            Journal::where('reference_type', 'booking_cost')
                ->where('reference_id', $referenceId)
                ->where('id', '!=', $latestJournal->id)
                ->delete();
        }

        // Perbaiki booking_id di journal entries
        $journals = Journal::where('reference_type', 'booking_cost')->get();
        foreach ($journals as $journal) {
            $bookingCost = BookingCost::find($journal->reference_id);
            if ($bookingCost) {
                $journal->entries()->update(['booking_id' => $bookingCost->booking_id]);
            }
        }

        $this->info('Duplikat journal berhasil dihapus dan booking_id diperbaiki.');
    }
}
