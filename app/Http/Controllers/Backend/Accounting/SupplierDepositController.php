<?php

namespace App\Http\Controllers\Backend\Accounting;

use App\Models\SupplierDeposit;
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
}
