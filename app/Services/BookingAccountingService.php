<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Journal;
use App\Services\Accounting\AccountMappingService;
use App\Services\Accounting\JournalBuilderService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookingAccountingService
{
    protected $journalBuilder;
    protected $accountMappingService;

    public function __construct(JournalBuilderService $journalBuilder, AccountMappingService $accountMappingService)
    {
        $this->journalBuilder = $journalBuilder;
        $this->accountMappingService = $accountMappingService;
    }

    public function handle(Booking $booking): void
    {
        try {
            match ($booking->status) {
                'booked' => $this->handleDownPayment($booking),
                'paid' => $this->handleFullPayment($booking),
                'finished' => $this->handleRevenueRecognition($booking),
                default => Log::warning('Status booking tidak dikenali', [
                    'booking_id' => $booking->id,
                    'status' => $booking->status
                ])
            };
        } catch (\Exception $e) {
            Log::error('Gagal memproses akuntansi booking', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    protected function handleDownPayment(Booking $booking): void
    {
        if ($this->hasJournal($booking, 'dp')) {
            Log::info('Jurnal DP sudah ada, tidak dibuat ulang', ['booking_id' => $booking->id]);
            return;
        }

        $accounts = $this->accountMappingService->getAccountsByTransactionType('uang_muka_pelanggan');

        $this->journalBuilder->create([
            'date' => Carbon::now(),
            'description' => 'DP Booking #' . $booking->code_booking,
            'reference_type' => Booking::class,
            'reference_id' => $booking->id,
            'journal_type' => 'dp',
            'entries' => [
                [
                    'account_id' => $accounts['debit'], // Kas/Bank
                    'booking_id' => $booking->id,
                    'debit' => $booking->down_paymet,
                    'credit' => 0,
                ],
                [
                    'account_id' => $accounts['credit'], // Utang DP Pelanggan
                    'booking_id' => $booking->id,
                    'debit' => 0,
                    'credit' => $booking->down_paymet,
                ],
            ],
        ]);
    }

    protected function handleFullPayment(Booking $booking): void
    {
        $this->deleteJournalByType($booking, 'revenue');

        if ($this->hasJournal($booking, 'pelunasan')) {
            Log::info('Jurnal pelunasan sudah ada, tidak dibuat ulang', ['booking_id' => $booking->id]);
            return;
        }

         
        // Menggunakan mapping yang sama untuk pelunasan (utang berkurang)
        $accounts = $this->accountMappingService->getAccountsByTransactionType('uang_muka_pelanggan');

        $this->journalBuilder->create([
            'date' => Carbon::now(),
            'description' => 'Pelunasan Booking #' . $booking->code_booking,
            'reference_type' => Booking::class,
            'reference_id' => $booking->id,
            'journal_type' => 'pelunasan',
            'entries' => [
                [
                    'account_id' => $accounts['debit'], // Kas/Bank
                    'booking_id' => $booking->id,
                    'debit' => $booking->remaining_costs,
                    'credit' => 0,
                ],
                [
                    'account_id' => $accounts['credit'], // Utang DP Pelanggan (berkurang)
                    'booking_id' => $booking->id,
                    'debit' => 0,
                    'credit' => $booking->remaining_costs,
                ],
            ],
        ]);
    }

    protected function handleRevenueRecognition(Booking $booking): void
    {
        $revenueAccounts = $this->accountMappingService->getAccountsByTransactionType('pengakuan_pendapatan');

        $entries = [
            [
                'account_id' => $revenueAccounts['debit'], // Pendapatan Diterima Dimuka (berkurang)
                'booking_id' => $booking->id,
                'debit' => $booking->total_price,
                'credit' => 0,
            ],
            [
                'account_id' => $revenueAccounts['credit'], // Pendapatan Tour
                'booking_id' => $booking->id,
                'debit' => 0,
                'credit' => $booking->total_price,
            ],
        ];

        $costsAlreadyJournaled = $booking->costs->filter(function ($cost) {
            return $cost->journal && $cost->journal->journal_type === 'cost';
        });

        if ($costsAlreadyJournaled->isEmpty()) {
            foreach ($booking->costs as $cost) {
                $biayaOperasionalAccounts = $this->accountMappingService->getAccountsByTransactionType('biaya_operasional');
                $entries[] = [
                    'account_id' => $cost->account_id, // Menggunakan account_id dari BookingCost
                    'booking_id' => $booking->id,
                    'debit' => $cost->amount,
                    'credit' => 0,
                ];
                $entries[] = [
                    'account_id' => $biayaOperasionalAccounts['credit'], // Kas/Bank
                    'booking_id' => $booking->id,
                    'debit' => 0,
                    'credit' => $cost->amount,
                ];
            }
        }

        $this->journalBuilder->create([
            'date' => now(),
            'description' => 'Pengakuan Pendapatan Booking #' . $booking->code_booking,
            'reference_type' => Booking::class,
            'reference_id' => $booking->id,
            'journal_type' => 'revenue',
            'entries' => $entries,
        ]);
    }

    protected function deleteRevenueJournal(Booking $booking): void
    {
        $existing = Journal::where('reference_type', Booking::class)
            ->where('reference_id', $booking->id)
            ->where('journal_type', 'revenue')
            ->first();

        if ($existing) {
            $existing->delete();
            Log::info('Jurnal revenue dihapus untuk rollback', ['booking_id' => $booking->id]);
        }
    }

    protected function deleteJournalByType(Booking $booking, string $type): void
    {
        $journals = Journal::where('reference_type', Booking::class)
            ->where('reference_id', $booking->id)
            ->where('journal_type', $type)
            ->get();

        foreach ($journals as $journal) {
            $journal->delete();
        }

        Log::info("Jurnal tipe '{$type}' dihapus untuk booking {$booking->code_booking}");
    }

    protected function hasJournal(Booking $booking, string $type): bool
    {
        return Journal::where('reference_type', Booking::class)
            ->where('reference_id', $booking->id)
            ->where('journal_type', $type)
            ->exists();
    }
}