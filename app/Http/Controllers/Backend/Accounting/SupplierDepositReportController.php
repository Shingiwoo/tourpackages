<?php

namespace App\Http\Controllers\Backend\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Accounting\SupplierDepositReportService;

class SupplierDepositReportController extends Controller
{
    public function index(Request $request)
    {
        $report = SupplierDepositReportService::generate($request->all());
        return response()->json($report);
    }
}
