<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Journal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixOldJournalData extends Command
{
    protected $signature = 'journals:fix-old-data';
    protected $description = 'Fix old journals that have null reference_type, reference_id, or journal_type';

    public function handle()
    {
        $this->info('Starting to fix old journals...');

        $journals = Journal::whereNull('reference_type')
            ->orWhereNull('reference_id')
            ->orWhereNull('journal_type')
            ->get();

        if ($journals->isEmpty()) {
            $this->info('No journals found that need fixing.');
            return 0;
        }

        DB::transaction(function () use ($journals) {
            foreach ($journals as $journal) {
                try {
                    $this->fixJournal($journal);
                } catch (\Exception $e) {
                    Log::error('Failed to fix journal ID: ' . $journal->id, [
                        'error' => $e->getMessage()
                    ]);
                    $this->error('Error fixing journal ID: ' . $journal->id);
                }
            }
        });

        $this->info('Finished fixing journals.');
        return 0;
    }

    private function fixJournal(Journal $journal)
    {
        $updated = false;

        if (is_null($journal->reference_type)) {
            // Misalnya bisa diisi 'Booking' default, atau cari dari entry?
            $journal->reference_type = 'Booking';
            $updated = true;
        }

        if (is_null($journal->reference_id)) {
            // Jika ada journal entries, coba ambil booking_id pertama
            $firstEntry = $journal->entries()->first();
            if ($firstEntry && $firstEntry->booking_id) {
                $journal->reference_id = $firstEntry->booking_id;
                $updated = true;
            } else {
                Log::warning('Cannot fix reference_id for journal ID: ' . $journal->id);
            }
        }

        if (is_null($journal->journal_type)) {
            $journal->journal_type = 'general'; // atau default lain
            $updated = true;
        }

        if ($updated) {
            $journal->save();
            $this->info('Fixed journal ID: ' . $journal->id);
        } else {
            $this->warn('No changes made for journal ID: ' . $journal->id);
        }
    }
}
