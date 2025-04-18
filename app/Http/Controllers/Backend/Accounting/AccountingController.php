<?php

namespace App\Http\Controllers\Backend\Accounting;

use App\Models\Booking;
use App\Http\Controllers\Controller;

class AccountingController extends Controller
{
    public function index()
    {
        $bookings = Booking::whereIn('status', ['booked', 'paid'])
            ->orderBy('created_at', 'desc')->get();
        return view('admin.accounting.index', compact('bookings'));
    }
}
