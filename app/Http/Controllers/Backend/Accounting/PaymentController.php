<?php

namespace App\Http\Controllers\Backend\Accounting;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Services\Accounting\AccountMappingService;
use App\Services\Accounting\BookingPaymentService;
use App\Services\Accounting\JournalBuilderService;

class PaymentController extends Controller
{
    public function index(Booking $booking)
    {
        $payments = $booking->payments;
        return view('admin.payments.index', compact('booking', 'payments'));
    }

    public function create(Booking $booking)
    {
        return view('admin.payments.add', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        try {
            Log::info('Payment store method initiated.', ['booking_id' => $booking->id]);

            $rules = [
                'type' => 'required|in:dp,pelunasan',
                'method' => 'required|in:tunai,transfer,virtual_account',
                'dp_installment' => 'nullable|integer|min:1',
                'ammount' => 'required|string',
                'payment_due_date' => 'nullable|date',
                'status' => 'required|in:waiting,terbayar,cancel',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Log::warning('Validation failed in payment store.', ['errors' => $validator->errors()]);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Format amount: remove commas
            $amount = str_replace(',', '', $request->ammount);
            if (!is_numeric($amount)) {
                throw new \Exception("Invalid amount format");
            }

            $paymentData = [
                'booking_id' => $booking->id,
                'type' => $request->type,
                'method' => $request->method,
                'dp_installment' => $request->dp_installment,
                'ammount' => $amount,
                'status' => $request->status,
            ];

            // Set payment_due_date
            $paymentData['payment_due_date'] = $request->filled('payment_due_date')
                ? Carbon::parse($request->payment_due_date)
                : Carbon::now()->addDays(2);

            // Create payment
            $payment = Payment::create($paymentData);
            // Log the payment creation
            Log::info('Payment created successfully.', ['payment_id' => $payment->id]);

            $notification = [
                'message' => 'Data Pembayaran berhasil dibuat.',
                'alert-type' => 'success'
            ];

            return redirect()->route('payments.index', $booking)->with($notification);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation exception in payment store.', ['error' => $e->getMessage()]);

            return redirect()->back()
                ->withErrors($e->validator) // Untuk menampilkan error validasi di form
                ->with([
                    'message' => 'Gagal menyimpan data pembayaran. Silakan periksa form kembali.',
                    'alert-type' => 'error',
                ])
                ->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error in payment store.', ['error' => $e->getMessage()]);
            $notification = [
                'message' => 'Gagal menyimpan data pembayaran. Error database.',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification)->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error in payment store.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $notification = [
                'message' => 'Terjadi kesalahan saat menyimpan data pembayaran.',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification)->withInput();
        }
    }

    public function show(Booking $booking, Payment $payment)
    {
        if ($payment->booking_id !== $booking->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.payments.show', compact('booking', 'payment'));
    }

    public function edit(Booking $booking, Payment $payment)
    {
        if ($payment->booking_id !== $booking->id) {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.payments.edit', compact('booking', 'payment'));
    }

    public function update(Request $request, Booking $booking, Payment $payment)
    {
        try {

            if ($payment->booking_id !== $booking->id) {
                abort(403, 'Unauthorized action.');
            }

            Log::info('Payment update method initiated.', ['booking_id' => $booking->id]);

            $rules = [
                'type' => 'required|in:dp,pelunasan',
                'method' => 'required|in:tunai,transfer,virtual_account',
                'dp_installment' => 'nullable|integer|min:1',
                'ammount' => 'required|string',
                'payment_due_date' => 'nullable|date',
                'status' => 'required|in:waiting,terbayar,cancel',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Log::warning('Validation failed in payment update.', ['errors' => $validator->errors()]);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Format amount: remove commas
            $amount = str_replace(',', '', $request->ammount);
            if (!is_numeric($amount)) {
                throw new \Exception("Invalid amount format");
            }

            $paymentData = [
                'booking_id' => $booking->id,
                'type' => $request->type,
                'method' => $request->method,
                'dp_installment' => $request->dp_installment,
                'ammount' => $amount,
                'status' => $request->status,
            ];

            // Set payment_due_date
            $paymentData['payment_due_date'] = $request->filled('payment_due_date')
                ? Carbon::parse($request->payment_due_date)
                : Carbon::now()->addDays(2);

            // Create payment
            $payment->update($paymentData);
            Log::info('Payment update successfully.', ['payment_id' => $payment->id]);

            $notification = [
                'message' => 'Data Pembayaran berhasil diperbaharui.',
                'alert-type' => 'success'
            ];

            return redirect()->route('payments.index', $booking)->with($notification);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation exception in payment update.', ['error' => $e->getMessage()]);

            return redirect()->back()
                ->withErrors($e->validator) // Untuk menampilkan error validasi di form
                ->with([
                    'message' => 'Gagal menyimpan data pembayaran. Silakan periksa form kembali.',
                    'alert-type' => 'error',
                ])
                ->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error in payment update.', ['error' => $e->getMessage()]);
            $notification = [
                'message' => 'Gagal menyimpan data pembayaran. Error database.',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification)->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error in payment update.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $notification = [
                'message' => 'Terjadi kesalahan saat menyimpan data pembayaran.',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification)->withInput();
        }
    }

    public function destroy(Booking $booking, Payment $payment)
    {
        if ($payment->booking_id !== $booking->id) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus bukti transfer jika ada
        if ($payment->proof_of_transfer) {
            Storage::disk('public')->delete($payment->proof_of_transfer);
        }

        $payment->delete();

        return redirect()->route('payments.index', $booking)->with('success', 'Pembayaran berhasil dihapus.');
    }

    // Fitur Pembatalan Otomatis (akan dipanggil oleh Artisan Command)
    public function cancelExpiredPayments()
    {
        $now = Carbon::now();

        // Proses pembayaran dengan default waktu 2 hari
        Payment::where('status', 'waiting')
            ->whereNull('payment_due_date')
            ->where('created_at', '<=', $now->subDays(2))
            ->update(['status' => 'cancel']);

        // Proses pembayaran dengan setting waktu dari form
        Payment::where('status', 'waiting')
            ->whereNotNull('payment_due_date')
            ->where('payment_due_date', '<=', $now)
            ->update(['status' => 'cancel']);

        Log::info('Pembayaran waiting yang melewati batas waktu telah dibatalkan.');
    }

    // Fitur Upload Bukti Transfer
    public function uploadProof(Request $request, Booking $booking, Payment $payment)
    {
        try {
            // Validasi authorization
            if ($payment->booking_id !== $booking->id || $payment->method !== 'transfer') {
                abort(403, 'Unauthorized action.');
            }

            // Validasi input
            $request->validate([
                'payment_proof' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png',
                    'max:2048'
                ],
            ], [
                'payment_proof.required' => 'File bukti pembayaran wajib diisi',
                'payment_proof.image' => 'File harus berupa gambar',
                'payment_proof.mimes' => 'Format file harus jpg, jpeg, png',
                'payment_proof.max' => 'Ukuran file maksimal 2MB'
            ]);

            if ($request->hasFile('payment_proof')) {
                // Debugging
                Log::info('File details', [
                    'original_name' => $request->file('payment_proof')->getClientOriginalName(),
                    'mime' => $request->file('payment_proof')->getMimeType()
                ]);

                // Hapus bukti transfer lama jika ada
                if ($payment->proof_of_transfer) {
                    Storage::disk('payment_proof')->delete($payment->proof_of_transfer);
                }

                // Simpan file
                $file = $request->file('payment_proof');
                $filename = date('YmdHi') . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('', $filename, 'payment_proof');

                // Update database
                $payment->update(['proof_of_transfer' => 'payment_proof/'.$path]);

                return redirect()->back()->with([
                    'message' => 'Bukti pembayaran berhasil diupload',
                    'alert-type' => 'success'
                ]);
            }

            return redirect()->back()->with([
                'message' => 'Tidak ada file yang diupload',
                'alert-type' => 'error'
            ]);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->with([
                    'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all()),
                    'alert-type' => 'error'
                ])
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Payment proof upload error: '.$e->getMessage());
            return redirect()->back()->with([
                'message' => 'Gagal mengupload bukti pembayaran: '.$e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }

    // Fitur Konfirmasi Pembayaran (Admin)
    public function confirmPayment(Booking $booking, Payment $payment)
    {
        if ($payment->booking_id !== $booking->id && $payment->status !== 'waiting') {
            abort(403, 'Unauthorized action.');
        }

        $payment->update(['status' => 'terbayar', 'payment_at' => now()]);

        // Inisialisasi JournalService
        $journalBuilder = app(JournalBuilderService::class);
        $accountMappingService = app(AccountMappingService::class);
        $bookingPaymentService = new BookingPaymentService($journalBuilder, $accountMappingService);


        // Jika pembayaran adalah dp ke - 2 dst, buat journal untuk DP
        if ($payment->type === 'dp' && $payment->dp_installment > 1) {
            // Buat jurnal untuk DP
            $bookingPaymentService->handle($booking, $payment);

            // Update down payment dan remaining costs pada booking            
            $updatedDPBooking = $booking->down_paymet + $payment->ammount;
            $booking->update(['down_paymet' => $updatedDPBooking]);

            $updatedBookingRemainingCost = $booking->remaining_costs - $booking->down_paymet;
            $booking->update(['remaining_costs' => $updatedBookingRemainingCost]);

            // Log informasi jurnal
            Log::info('DP payment confirmed and journal created.', [
                'payment_id' => $payment->id,
                'booking_id' => $booking->id,
                'dp_installment' => $payment->dp_installment
            ]);
        }
        // Jika pembayaran adalah DP, update booking status
        if ($payment->type === 'dp' && $payment->dp_installment > 0) {
            $booking->update(['status' => 'booked']);
        }
        // Jika pembayaran adalah pelunasan, update booking status
        elseif ($payment->type === 'pelunasan') {
            $booking->update(['status' => 'paid']);
        }

        Log::info('Payment confirmed successfully.', ['payment_id' => $payment->id, 'booking_id' => $booking->id]);

        // Kirim notifikasi toast
        $notification = [
            'message' => 'Pembayaran berhasil dikonfirmasi.',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.bookings')->with($notification);
    }

    // Fitur Pembatalan Manual (Admin)
    public function cancelPayment(Booking $booking, Payment $payment)
    {
        if ($payment->booking_id !== $booking->id && $payment->status !== 'waiting') {
            abort(403, 'Unauthorized action.');
        }

        $payment->update(['status' => 'cancel']);

        return redirect()->back()->with('success', 'Pembayaran berhasil dibatalkan.');
    }
}
