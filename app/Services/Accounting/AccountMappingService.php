<?php

namespace App\Services\Accounting;

use App\Models\Account;

class AccountMappingService
{
    /**
     * Return account IDs used for specific transaction types.
     */
    public function getAccountsByTransactionType(string $type): array
    {
        switch ($type) {
            case 'deposit_supplier':
                return [
                    'debit' => $this->getAccountIdByCode('1-003'), // Deposit ke Supplier
                    'credit' => $this->getAccountIdByCode('1-001'), // Kas/Bank
                ];

            case 'penjualan_tiket':
                return [
                    'debit' => $this->getAccountIdByCode('5-001-006'), // HPP Tiket
                    'credit' => $this->getAccountIdByCode('1-003'),     // Deposit ke Supplier
                ];

            case 'uang_muka_pelanggan':
                return [
                    'debit' => $this->getAccountIdByCode('1-001'),     // Kas/Bank
                    'credit' => $this->getAccountIdByCode('2-002'),    // Pendapatan Diterima Dimuka
                ];

            case 'pengakuan_pendapatan':
                return [
                    'debit' => $this->getAccountIdByCode('2-002'),     // Pendapatan Diterima Dimuka
                    'credit' => $this->getAccountIdByCode('4-001'),    // Pendapatan Tour
                ];

            case 'biaya_operasional':
                return [
                    'debit' => $this->getAccountIdByCode('5-002-001'), // Beban Marketing (default)
                    'credit' => $this->getAccountIdByCode('1-001'),     // Kas/Bank
                ];

            default:
                throw new \Exception("Transaction type '{$type}' is not mapped yet.");
        }
    }

    /**
     * Helper to get account ID from code.
     */
    protected function getAccountIdByCode(string $code): int
    {
        $account = Account::where('code', $code)->first();

        if (!$account) {
            throw new \Exception("Account with code {$code} not found.");
        }

        return $account->id;
    }
}
