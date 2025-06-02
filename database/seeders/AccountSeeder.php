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
            // ASET
            ['code' => '1-001', 'name' => 'Kas/Bank', 'type' => 'asset', 'category' => 'Current Asset'],
            ['code' => '1-002', 'name' => 'Piutang Pelanggan', 'type' => 'asset', 'category' => 'Current Asset'],
            ['code' => '1-003', 'name' => 'Deposit ke Supplier', 'type' => 'asset', 'category' => 'Current Asset'],
            ['code' => '1-004', 'name' => 'Perlengkapan', 'type' => 'asset', 'category' => 'Non-Current Asset'],
            ['code' => '1-005', 'name' => 'Kendaraan', 'type' => 'asset', 'category' => 'Fixed Asset'],
            ['code' => '1-006', 'name' => 'Akumulasi Penyusutan', 'type' => 'asset', 'category' => 'Contra Asset'],

            // KEWAJIBAN
            ['code' => '2-001', 'name' => 'Utang DP Pelanggan', 'type' => 'liability', 'category' => 'Current Liability'],
            ['code' => '2-002', 'name' => 'Pendapatan Diterima Dimuka', 'type' => 'liability', 'category' => 'Current Liability'],
            ['code' => '2-003', 'name' => 'Utang Usaha', 'type' => 'liability', 'category' => 'Current Liability'],
            ['code' => '2-004', 'name' => 'Pajak Terutang', 'type' => 'liability', 'category' => 'Current Liability'],

            // EKUITAS
            ['code' => '3-001', 'name' => 'Modal Pemilik', 'type' => 'equity', 'category' => 'Equity'],
            ['code' => '3-002', 'name' => 'Laba Ditahan', 'type' => 'equity', 'category' => 'Equity'],

            // PENDAPATAN
            ['code' => '4-001', 'name' => 'Pendapatan Tour', 'type' => 'revenue', 'category' => 'Revenue'],
            ['code' => '4-002', 'name' => 'Pendapatan Tiket', 'type' => 'revenue', 'category' => 'Revenue'],
            ['code' => '4-003', 'name' => 'Pendapatan Lain-lain', 'type' => 'revenue', 'category' => 'Other Income'],

            // BEBAN HPP (COST OF SALES)
            ['code' => '5-001', 'name' => 'Beban HPP', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-001', 'name' => 'Beban Sewa Kendaraan', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-002', 'name' => 'Beban BBM', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-003', 'name' => 'Beban Parkir', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-004', 'name' => 'Beban Konsumsi', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-005', 'name' => 'Beban Penginapan', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-006', 'name' => 'Beban Tiket', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-007', 'name' => 'Beban Gaji Supir perJob', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-008', 'name' => 'Beban Transport Team ', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-009', 'name' => 'Beban Konsumsi Team', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-010', 'name' => 'Beban Penginapan Team', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-011', 'name' => 'Beban BBM Team', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-012', 'name' => 'Beban Seragam Team', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-013', 'name' => 'Beban Biaya Alat Pendukung', 'type' => 'expense', 'category' => 'Cost of Sales'],
            ['code' => '5-001-014', 'name' => 'Beban Fasilitas Tour', 'type' => 'expense', 'category' => 'Cost of Sales'],

            // BIAYA OPERASIONAL
            ['code' => '5-002-001', 'name' => 'Beban Marketing', 'type' => 'expense', 'category' => 'Operating Expense'],
            ['code' => '5-002-002', 'name' => 'Beban Internet', 'type' => 'expense', 'category' => 'Operating Expense'],
            ['code' => '5-002-003', 'name' => 'Biaya Listrik dan Air', 'type' => 'expense', 'category' => 'Operating Expense'],
            ['code' => '5-002-004', 'name' => 'Biaya Transportasi Internal', 'type' => 'expense', 'category' => 'Operating Expense'],
            ['code' => '5-002-005', 'name' => 'Beban Gaji Pegawai Tetap', 'type' => 'expense', 'category' => 'Operating Expense'],
            ['code' => '5-002-006', 'name' => 'Biaya Kantor (ATK, dll)', 'type' => 'expense', 'category' => 'Operating Expense'],
            ['code' => '5-002-007', 'name' => 'Beban Penyusutan', 'type' => 'expense', 'category' => 'Operating Expense'],
        ];

        foreach ($accounts as $account) {
            Account::updateOrCreate(['code' => $account['code']], $account);
        }
    }

}
