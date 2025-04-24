<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Journal;
use App\Models\BookingCost;

class FixBookingIdInJournalEntries extends Command
{
    protected $signature = 'fix:booking-journal-entries';
    protected $description = 'Perbaiki booking_id yang null di journal_entries berdasarkan booking_cost';

    public function handle()
    {
        $journals = Journal::where('reference_type', 'booking_cost')->get();
        $updated = 0;

        foreach ($journals as $journal) {
            $bookingCost = BookingCost::find($journal->reference_id);

            if (!$bookingCost) {
                $this->warn("BookingCost tidak ditemukan untuk journal ID: {$journal->id}");
                continue;
            }

            foreach ($journal->entries as $entry) {
                if (is_null($entry->booking_id)) {
                    $entry->booking_id = $bookingCost->booking_id;
                    $entry->save();
                    $updated++;
                }
            }
        }

        $this->info("Selesai! {$updated} journal_entries berhasil diperbarui.");
    }
}
