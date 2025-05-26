<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Backend\Accounting\PaymentController;

class CancelWaitingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-waiting-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membatalkan pembayaran yang berstatus menunggu dan telah melewati batas waktu.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paymentController = app(PaymentController::class);
        $paymentController->cancelExpiredPayments();

        $this->info('Pengecekan dan pembatalan pembayaran menunggu telah selesai.');
    }
}
