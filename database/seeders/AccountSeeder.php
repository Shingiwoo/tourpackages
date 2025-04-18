<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            ['code' => '1-001', 'name' => 'Kas/Bank', 'type' => 'asset', 'category' => 'Current Asset'],
            ['code' => '1-002', 'name' => 'Piutang Pelanggan', 'type' => 'asset', 'category' => 'Current Asset'],
            ['code' => '2-001', 'name' => 'Utang DP Pelanggan', 'type' => 'liability', 'category' => 'Current Liability'],
            ['code' => '3-001', 'name' => 'Modal Pemilik', 'type' => 'equity', 'category' => 'Equity'],
            ['code' => '4-001', 'name' => 'Pendapatan Tour', 'type' => 'revenue', 'category' => 'Revenue'],
            ['code' => '5-001', 'name' => 'Beban HPP', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-001', 'name' => 'Beban Sewa Kendaraan', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-002', 'name' => 'Beban BBM', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-003', 'name' => 'Beban Parkir', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-004', 'name' => 'Beban Konsumsi', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-005', 'name' => 'Beban Penginapan', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-006', 'name' => 'Beban Tiket', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-002', 'name' => 'Beban Marketing', 'type' => 'expense', 'category' => 'Operating Expense'],
        ];

        foreach ($accounts as $account) {
            Account::updateOrCreate(['code' => $account['code']], $account);
        }
    }
}
