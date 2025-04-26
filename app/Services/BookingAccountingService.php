<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\Account;
use Carbon\Carbon;

class BookingAccountingService
{
    public function handle(Booking $booking)
    {
        // Hapus semua journal terkait booking ini sebelum membuat baru
        $this->deleteExistingBookingJournals($booking);

        switch ($booking->status) {
            case 'booked':
                $this->handleDownPayment($booking);
                break;
            case 'paid':
                $this->handleFullPayment($booking);
                break;
            case 'finished':
                $this->handleRevenueRecognition($booking);
                break;
        }
    }

    protected function deleteExistingBookingJournals(Booking $booking)
    {
        // Hapus semua journal dan entries terkait booking ini
        Journal::where('reference_type', 'booking')
            ->where('reference_id', $booking->id)
            ->delete();
    }

    protected function handleDownPayment(Booking $booking)
    {
        $journal = Journal::create([
            'description' => 'DP Booking #' . $booking->code_booking,
            'date' => Carbon::now(),
        ]);

        $this->createEntry($journal->id, $booking->id, '1-001', $booking->down_paymet, 0); // Kas
        $this->createEntry($journal->id, $booking->id, '2-001', 0, $booking->down_paymet); // Utang DP
    }

    protected function handleFullPayment(Booking $booking)
    {
        $journal = Journal::create([
            'description' => 'Pelunasan Booking #' . $booking->code_booking,
            'date' => Carbon::now(),
        ]);

        $this->createEntry($journal->id, $booking->id, '1-001', $booking->remaining_costs, 0); // Kas
        $this->createEntry($journal->id, $booking->id, '2-001', 0, $booking->remaining_costs); // Utang DP
    }

    protected function handleRevenueRecognition(Booking $booking)
    {
        $journal = Journal::create([
            'description' => 'Pengakuan Pendapatan Booking #' . $booking->code_booking,
            'date' => Carbon::now(),
        ]);

        // Pendapatan
        $this->createEntry($journal->id, $booking->id, '2-001', $booking->total_price, 0); // DP dikurangi
        $this->createEntry($journal->id, $booking->id, '4-001', 0, $booking->total_price); // Pendapatan

        // HPP (BookingCost)
        foreach ($booking->costs as $cost) {
            $this->createEntry($journal->id, $booking->id, $cost->account->code, $cost->amount, 0);
            $this->createEntry($journal->id, $booking->id, '1-001', 0, $cost->amount); // Kredit kas
        }
    }

    protected function createEntry($journalId, $bookingId, $accountCode, $debit, $credit)
    {
        $account = Account::where('code', $accountCode)->firstOrFail();

        JournalEntry::create([
            'journal_id' => $journalId,
            'booking_id' => $bookingId,
            'account_id' => $account->id,
            'debit' => $debit,
            'credit' => $credit,
        ]);
    }
}
