<?php

namespace App\Http\Controllers\Backend\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LedgerService;
use App\Models\Account;

class LedgerController extends Controller
{
    public function index(Request $request, LedgerService $ledgerService)
    {
        $filters = $request->only(['start_date', 'end_date', 'account_id']);
        $accounts = Account::all();
        $ledgers = $ledgerService->generate($filters);

        return view('admin.ledger.index', compact('ledgers', 'accounts', 'filters'));
    }
}
