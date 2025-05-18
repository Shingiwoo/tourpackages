<?php

namespace App\Http\Controllers\Backend\Accounting;

use Illuminate\Http\Request;
use App\Models\SupplierInvoice;
use App\Models\SupplierPayment;
use App\Http\Controllers\Controller;
use App\Services\SupplierPaymentSettlementService;

class SupplierPaymentController extends Controller
{
    public function index()
    {
        $payments = SupplierPayment::with('supplier', 'invoice')->latest()->paginate(25);
        return response()->json($payments);
    }

    public function show($id)
    {
        $payment = SupplierPayment::with(['supplier', 'invoice', 'histories'])->findOrFail($id);
        return response()->json($payment);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'supplier_invoice_id' => 'nullable|exists:supplier_invoices,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Jika ada invoice_id → auto-settle
        if ($validated['supplier_invoice_id']) {
            $invoice = SupplierInvoice::findOrFail($validated['supplier_invoice_id']);

            SupplierPaymentSettlementService::settle(
                $invoice,
                $validated['amount'],
                $validated['notes'] ?? 'Manual payment via controller'
            );

            return response()->json([
                'message' => 'Payment settled with invoice automatically',
                'invoice_id' => $invoice->id,
            ]);
        }

        // Jika tidak terkait invoice langsung → buat payment standalone
        $payment = SupplierPayment::create($validated);

        return response()->json([
            'message' => 'Payment saved successfully',
            'payment' => $payment,
        ]);
    }
}
