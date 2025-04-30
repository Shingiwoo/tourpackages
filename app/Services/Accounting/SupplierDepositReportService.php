<?php

namespace App\Services\Accounting;

use App\Models\SupplierDeposit;

class SupplierDepositReportService
{
    public static function generate($filters = [])
    {
        $query = SupplierDeposit::query()
            ->with('supplier')
            ->withSum('histories as total_used', 'amount')
            ->selectRaw('
                supplier_deposits.*,
                (amount - COALESCE((SELECT SUM(amount) FROM supplier_payment_histories WHERE supplier_payment_histories.supplier_deposit_id = supplier_deposits.id), 0)) AS remaining
            ');

        if (!empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        if (!empty($filters['booking_id'])) {
            $query->where('booking_id', $filters['booking_id']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('date', [$filters['start_date'], $filters['end_date']]);
        }

        return $query->orderBy('date', 'desc')->get();
    }
}
