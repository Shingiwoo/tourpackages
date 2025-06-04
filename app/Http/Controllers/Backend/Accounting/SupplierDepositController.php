<?php

namespace App\Http\Controllers\Backend\Accounting;

use App\Models\Booking;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\SupplierDeposit;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class SupplierDepositController extends Controller
{
    public function listBySupplier($id)
    {
        $deposits = SupplierDeposit::where('supplier_id', $id)
            ->withSum('histories as used_amount', 'amount')
            ->get()
            ->map(function ($deposit) {
                $deposit->remaining = $deposit->amount - $deposit->used_amount;
                return $deposit;
            });

        return response()->json($deposits);
    }

    public function index()
    {
        $bookings = Booking::whereIn('status', ['booked', 'paid'])
            ->orderBy('created_at', 'desc')->get();
        $suppliers = Supplier::all();
        $deposits = SupplierDeposit::all();
        return view('admin.supplier.deposit-index', compact('deposits', 'suppliers', 'bookings'));
    }    

    public function store(Request $request)
    {
        try {
            Log::info('Supplier Deposit data start.');

            // Bersihkan format angka sebelum validasi
            $request->merge([
                'amount' => str_replace(',', '', $request->amount),
                'remaining_amount' => $request->filled('remaining_amount') ? str_replace(',', '', $request->remaining_amount) : '0'
            ]);

            // Validate input
            $validatedData = $request->validate([
                'Date' => 'required|date',
                'supplierName' => 'required|string',
                'amount' => 'required|numeric|min:1',
                'booking_id' => 'nullable|exists:bookings,id',
                'remaining_amount' => 'nullable|numeric|min:0'
            ]);

            // Mapping data untuk disimpan
            $depositData = [
                'date' => $validatedData['Date'],
                'supplier_name' => $validatedData['supplierName'],
                'amount' => $validatedData['amount'],
                'booking_id' => $validatedData['booking_id'],
                'remaining_amount' => $validatedData['remaining_amount'] ?? '0',
            ];

            // Create new record in database
            SupplierDeposit::create($depositData);

            Log::info('Supplier Deposit data saved successfully');

            return redirect()->back()->with([
                'message' => 'Supplier Deposit Data Saved',
                'alert-type' => 'success',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Supplier validation failed', [
                'error' => $e->getMessage(),
                'errors' => $e->errors(),
            ]);
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Error saving supplier deposit data', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with([
                'message' => 'Error saving supplier deposit data. Please try again.',
                'alert-type' => 'error',
            ])->withInput();
        }
    }

    public function edit($id)
    {
        $deposit = SupplierDeposit::findOrFail($id);
        $bookings = Booking::whereIn('status', ['booked', 'paid'])
            ->orderBy('created_at', 'desc')->get();
        $suppliers = Supplier::all();
        return view('admin.supplier.deposit-edit', compact('deposit', 'suppliers', 'bookings'));
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Supplier Deposit update data start.');

            // Bersihkan format angka sebelum validasi
            $request->merge([
                'amount' => str_replace(',', '', $request->amount),
                'remaining_amount' => $request->filled('remaining_amount') ? str_replace(',', '', $request->remaining_amount) : '0'
            ]);

            // Validate input
            $validatedData = $request->validate([
                'Date' => 'required|date',
                'supplierName' => 'required|string',
                'amount' => 'required|numeric|min:1',
                'booking_id' => 'nullable|exists:bookings,id',
                'remaining_amount' => 'nullable|numeric|min:0'
            ]);

            // Temukan data berdasarkan ID
            $depositData = SupplierDeposit::findOrFail($id);

            // Mapping data untuk disimpan
            $depositData->update([
                'date' => $validatedData['Date'],
                'supplier_name' => $validatedData['supplierName'],
                'amount' => $validatedData['amount'],
                'booking_id' => $validatedData['booking_id'],
                'remaining_amount' => $validatedData['remaining_amount'] ?? '0',
            ]);

            Log::info('Supplier Deposit data updated successfully');

            return redirect()->route('all.supplier-deposits')->with([
                'message' => 'Supplier Deposit Data Updated',
                'alert-type' => 'success',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Supplier validation failed', [
                'error' => $e->getMessage(),
                'errors' => $e->errors(),
            ]);
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Error update supplier deposit data', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with([
                'message' => 'Error update supplier deposit data. Please try again.',
                'alert-type' => 'error',
            ])->withInput();
        }
    }

    /**
     * Remove the specified supplier deposit from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Log::info('Attempting to delete supplier deposit', ['deposit_id' => $id]);

            // Cari deposit atau gagal dengan 404
            $deposit = SupplierDeposit::findOrFail($id);

            // Lakukan soft delete jika menggunakan SoftDeletes
            // atau hapus permanen jika tidak
            $deposit->delete();

            Log::info('Supplier deposit deleted successfully', ['deposit_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Supplier deposit deleted successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Supplier deposit not found', [
                'error' => $e->getMessage(),
                'deposit_id' => $id
            ]);

            return response()->json([
                'info' => true,
                'message' => 'Supplier deposit not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error deleting supplier deposit', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
                'deposit_id' => $id
            ]);

            return response()->json([
                'warning' => true,
                'message' => 'Error deleting supplier deposit. Please try again.'
            ], 500);
        }
    }
}
