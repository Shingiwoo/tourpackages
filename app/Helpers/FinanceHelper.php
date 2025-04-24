<?php

namespace App\Helpers;

class FinanceHelper
{
    public static function calculateBookingHpp($booking)
    {
        // Hitung biaya langsung (akun HPP)
        $totalCost = $booking->journalEntries
            ->filter(fn ($entry) =>
                $entry->debit > 0 &&
                str_starts_with($entry->account->code ?? '', '5-001')
            )
            ->sum('debit');

        // Daftar akun kas (bisa disesuaikan)
        $kasBankAccounts = ['1-001'];

        // Hitung pendapatan (uang masuk ke kas)
        $totalIncome = $booking->journalEntries
            ->filter(fn ($entry) =>
                $entry->debit > 0 &&
                in_array($entry->account->code ?? '', $kasBankAccounts)
            )
            ->sum('debit');

        // Hitung HPP dan Margin
        $hpp = $totalIncome > 0 ? ($totalCost / $totalIncome) * 100 : 0;
        $margin = $totalIncome - $totalCost;

        return [
            'total_cost' => $totalCost,
            'total_income' => $totalIncome,
            'hpp_percent' => round($hpp, 2),
            'margin' => $margin
        ];
    }
}
