<?php

namespace App\Services\Accounting;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Journal;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use App\Services\Accounting\AccountMappingService;
use App\Services\Accounting\JournalBuilderService;

class BookingPaymentService
{
    protected $journalBuilder;
    protected $accountMappingService;

    public function __construct(JournalBuilderService $journalBuilder, AccountMappingService $accountMappingService)
    {
        $this->journalBuilder = $journalBuilder;
        $this->accountMappingService = $accountMappingService;
    }

    public function handle(Booking $booking, Payment $payment): void
    {
        try {
            match ($payment->status) {
                'terbayar' => $this->handleDownPaymentInstallment($booking, $payment),
                default => Log::warning('Status payment tidak dikenali', [
                    'booking_id' => $booking->id,
                    'status' => $payment->status
                ])
            };
        } catch (\Exception $e) {
            Log::error('Gagal memproses akuntansi payment', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    protected function handleDownPaymentInstallment(Booking $booking, Payment $payment): void
    {
        if ($this->hasJournal($booking, 'dp ke - ' . $payment->dp_installment)) {
            Log::info('Tidak dibuat ulang, karena Jurnal DP ke - ' . $payment->dp_installment . ' sudah ada' . [', booking_id : ' => $booking->id]);
            return;
        }

        $accounts = $this->accountMappingService->getAccountsByTransactionType('uang_muka_pelanggan');

        $this->journalBuilder->create([
            'date' => Carbon::now(),
            'description' => 'DP Booking ke - ' . $payment->dp_installment . ' Booking #' . $booking->code_booking,
            'reference_type' => Booking::class,
            'reference_id' => $booking->id,
            'journal_type' => 'dp ke - ' . $payment->dp_installment,
            'entries' => [
                [
                    'account_id' => $accounts['debit'],
                    'booking_id' => $booking->id,
                    'debit' => $payment->ammount,
                    'credit' => 0,
                ],
                [
                    'account_id' => $accounts['credit'],
                    'booking_id' => $booking->id,
                    'debit' => 0,
                    'credit' => $payment->ammount,
                ],
            ],
        ]);
    }

    protected function hasJournal(Booking $booking, string $type): bool
    {
        return Journal::where('reference_type', Booking::class)
            ->where('reference_id', $booking->id)
            ->where('journal_type', $type)
            ->exists();
    }
}
