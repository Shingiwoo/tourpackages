<?php

namespace App\Http\Controllers\Backend\Accounting;

use App\Models\Booking;
use App\Http\Controllers\Controller;

class AccountingController extends Controller
{
    public function index()
    {
        $bookings = Booking::whereIn('status', ['booked', 'paid'])
            ->with([
                'bookingList' => function ($query) {
                    $query->select('id', 'booking_id', 'agen_id', 'package_id');
                },
                'bookingList.agen' => function ($query) {
                    $query->select('id', 'username', 'name', 'company');
                },
                'bookingList.packageOneDay' => function ($query) {
                    $query->select('id', 'name_package');
                },
                'bookingList.packageTwoDay' => function ($query) {
                    $query->select('id', 'name_package');
                },
                'bookingList.packageThreeDay' => function ($query) {
                    $query->select('id', 'name_package');
                },
                'bookingList.packageFourDay' => function ($query) {
                    $query->select('id', 'name_package');
                },
                'bookingList.customPackage' => function ($query) {
                    $query->select('id', 'custompackage');
                },
                'bookingList.rentService' => function ($query) {
                    $query->select('id', 'name');
                }
            ])
            ->orderBy('created_at', 'desc')
        ->get();

        //dd($bookings->toArray());


        return view('admin.accounting.index', compact('bookings'));
    }
}
