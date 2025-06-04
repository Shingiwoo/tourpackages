<?php

namespace App\Http\Controllers\Backend\Accounting;


use Illuminate\Http\Request;
use App\Models\SupplierInvoice;
use App\Http\Controllers\Controller;
use App\Services\SupplierPaymentSettlementService;
use App\Services\Accounting\SupplierInvoiceJournalService;

class SupplierInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplierInvoice::query();

        if ($request->filled('supplier_name')) {
            $query->where('supplier_name', 'like', '%' . $request->supplier_name . '%');
        }

        $invoices = $query->orderBy('date', 'desc')->paginate(20);

        return response()->json($invoices);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'booking_id' => 'nullable|integer',
            'invoice_number' => 'required|string|max:255|unique:supplier_invoices',
            'date' => 'required|date',
            'due_date' => 'nullable|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $invoice = SupplierInvoice::create($validated);

        // Setelah create invoice
        SupplierInvoiceJournalService::create($invoice);

        // Setelah invoice dibuat, langsung settle dari DP!
        SupplierPaymentSettlementService::settle($invoice, $invoice->amount);

        return response()->json($invoice);
    }

    public function show($id)
    {
        $invoice = SupplierInvoice::findOrFail($id);

        return response()->json($invoice);
    }
}
