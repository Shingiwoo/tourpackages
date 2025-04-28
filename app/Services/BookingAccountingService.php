<?php

namespace App\Services;

use App\Models\Booking;
use App\Services\Accounting\JournalBuilderService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookingAccountingService
{
    protected $journalBuilder;

    public function __construct(JournalBuilderService $journalBuilder)
    {
        $this->journalBuilder = $journalBuilder;
    }

    /**
     * Handle booking accounting based on status
     */
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
        $this->journalBuilder->create([
            'date' => Carbon::now(),
            'description' => 'DP Booking #' . $booking->code_booking,
            'reference_type' => Booking::class,
            'reference_id' => $booking->id,
            'journal_type' => 'dp', // <-- ini
            'entries' => [
                [
                    'account_code' => '1-001',
                    'booking_id' => $booking->id,
                    'debit' => $booking->down_paymet,
                    'credit' => 0,
                ],
                [
                    'account_code' => '2-001',
                    'booking_id' => $booking->id,
                    'debit' => 0,
                    'credit' => $booking->down_paymet,
                ],
            ],
        ]);
    }

    protected function handleFullPayment(Booking $booking): void
    {
        $this->journalBuilder->create([
            'date' => Carbon::now(),
            'description' => 'Pelunasan Booking #' . $booking->code_booking,
            'reference_type' => Booking::class,
            'reference_id' => $booking->id,
            'journal_type' => 'pelunasan',
            'entries' => [
                [
                    'account_code' => '1-001',
                    'booking_id' => $booking->id,
                    'debit' => $booking->remaining_costs,
                    'credit' => 0,
                ],
                [
                    'account_code' => '2-001',
                    'booking_id' => $booking->id,
                    'debit' => 0,
                    'credit' => $booking->remaining_costs,
                ],
            ],
        ]);
    }

    protected function handleRevenueRecognition(Booking $booking): void
    {
        $entries = [
            [
                'account_code' => '2-001',
                'booking_id' => $booking->id,
                'debit' => $booking->total_price,
                'credit' => 0,
            ],
            [
                'account_code' => '4-001',
                'booking_id' => $booking->id,
                'debit' => 0,
                'credit' => $booking->total_price,
            ],
        ];

        foreach ($booking->costs as $cost) {
            $entries[] = [
                'account_code' => $cost->account->code,
                'booking_id' => $booking->id,
                'debit' => $cost->amount,
                'credit' => 0,
            ];
            $entries[] = [
                'account_code' => '1-001',
                'booking_id' => $booking->id,
                'debit' => 0,
                'credit' => $cost->amount,
            ];
        }

        $this->journalBuilder->create([
            'date' => Carbon::now(),
            'description' => 'Pengakuan Pendapatan Booking #' . $booking->code_booking,
            'reference_type' => Booking::class,
            'reference_id' => $booking->id,
            'journal_type' => 'revenue',
            'entries' => $entries,
        ]);
    }
}
