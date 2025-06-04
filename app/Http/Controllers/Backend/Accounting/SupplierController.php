<?php

namespace App\Http\Controllers\Backend\Accounting;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('admin.supplier.index', compact('suppliers'));
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);

        return view('admin.supplier.index', compact('supplier'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('Supplier data start.');
            //Log::info('Supplier data start.', ['request' => $request->except('_token')]);

            // Validate input
            $validatedData = $request->validate([
                'SupplierName' => 'required|string|max:100',
                'SupplierPhone' => 'required|numeric',
                'SupplierEmail' => 'nullable|string|email',
                'SupplierBank' => 'required|string',
                'SupplierAccountName' => 'required|string',
                'SupplierNoRek' => 'required|numeric',
                'SupplierAddress' => 'required|string|max:255',
                'ContactPerson' => 'nullable|string',
                'ContactPhone' => 'nullable|numeric',
                'ContactEmail' => 'nullable|email',
                'SupplierNote' => 'nullable|string|max:255',
            ]);

            // Mapping nama input form ke nama kolom database
            $supplierData = [
                'name' => $validatedData['SupplierName'],
                'phone' => $validatedData['SupplierPhone'],
                'address' => $validatedData['SupplierAddress'],
                'email' => $validatedData['SupplierEmail'],
                'contact_person' => $validatedData['ContactPerson'],
                'contact_phone' => $validatedData['ContactPhone'],
                'contact_email' => $validatedData['ContactEmail'],
                'account_name' => $validatedData['SupplierAccountName'],
                'bank_account' => $validatedData['SupplierNoRek'],
                'bank_name' => $validatedData['SupplierBank'],
                'notes' => $validatedData['SupplierNote'],
            ];

            // Create new record in database
            Supplier::create($supplierData);

            Log::info('Supplier data saved successfully');
            //Log::info('Supplier data saved successfully.', ['supplier_data' => $supplierData]);

            return redirect()->back()->with([
                'message' => 'Supplier Data Saved',
                'alert-type' => 'success',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Supplier validation failed', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Error saving supplier data', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with([
                'message' => 'Error saving supplier data. Please try again.',
                'alert-type' => 'error',
            ])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Supplier update data start.');

            // Validate input
            $validatedData = $request->validate([
                'SupplierName' => 'required|string|max:100',
                'SupplierPhone' => 'required|numeric',
                'SupplierEmail' => 'nullable|string|email',
                'SupplierBank' => 'required|string',
                'SupplierAccountName' => 'required|string',
                'SupplierNoRek' => 'required|numeric',
                'SupplierAddress' => 'required|string|max:255',
                'ContactPerson' => 'nullable|string',
                'ContactPhone' => 'nullable|numeric',
                'ContactEmail' => 'nullable|email',
                'SupplierNote' => 'nullable|string|max:255',
            ]);

            // Find and update the supplier
            $supplier = Supplier::findOrFail($id);

            $supplier->update([
                'name' => $validatedData['SupplierName'],
                'phone' => $validatedData['SupplierPhone'],
                'address' => $validatedData['SupplierAddress'],
                'email' => $validatedData['SupplierEmail'],
                'contact_person' => $validatedData['ContactPerson'],
                'contact_phone' => $validatedData['ContactPhone'],
                'contact_email' => $validatedData['ContactEmail'],
                'account_name' => $validatedData['SupplierAccountName'],
                'bank_account' => $validatedData['SupplierNoRek'],
                'bank_name' => $validatedData['SupplierBank'],
                'notes' => $validatedData['SupplierNote'],
            ]);

            Log::info('Supplier data updated successfully');

            return redirect()->back()->with([
                'message' => 'Supplier Data Updated',
                'alert-type' => 'success',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Supplier validation failed', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Error updating supplier data', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with([
                'message' => 'Error updating supplier data. Please try again.',
                'alert-type' => 'error',
            ])->withInput();
        }
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('all.suppliers')->with('success', 'Supplier deposit deleted successfully.');
    }
}
