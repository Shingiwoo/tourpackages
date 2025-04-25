<?php

namespace App\Http\Controllers\Backend\Accounting;

use App\Http\Controllers\Controller;
use App\Helpers\FinanceHelper;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function hpp(Request $request)
{
    $range = $request->get('range', 'week');

    switch ($range) {
        case 'month':
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            break;
        case 'year':
            $start = Carbon::now()->startOfYear();
            $end = Carbon::now()->endOfYear();
            break;
        case 'custom':
            $start = Carbon::parse($request->get('start'));
            $end = Carbon::parse($request->get('end'));
            break;
        case 'week':
        default:
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
            break;
    }

    $finance = FinanceHelper::calculateHppForRange($start, $end);

    return view('admin.reports.hpp', [
        'range' => $range,
        'start' => $start,
        'end' => $end,
        'finance' => $finance
    ]);
}
}
