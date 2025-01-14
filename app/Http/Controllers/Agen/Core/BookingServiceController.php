<?php

namespace App\Http\Controllers\Agen\Core;


use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\PackageOneDay;
use App\Models\PackageTwoDay;
use App\Models\PackageFourDay;
use App\Models\PackageThreeDay;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BookingServiceController extends Controller
{
    public function AllBooking()
    {
        $agen = Auth::user();

        return view('agen.booking.all_booking');
    }

    public function AddBooking()
    {
        $agen = Auth::user();

        $packOneday = PackageOneDay::where('agen_id', $agen->id)
        ->with(['destinations', 'prices', 'regency'])->get();
        $packTwoday = PackageTwoDay::where('agen_id', $agen->id)
        ->with(['destinations', 'prices', 'regency'])->get();
        $packThreeday = PackageThreeDay::where('agen_id', $agen->id)
        ->with(['destinations', 'prices', 'regency'])->get();
        $packFourday = PackageFourDay::where('agen_id', $agen->id)
        ->with(['destinations', 'prices', 'regency'])->get();

        // Gabungkan semua paket menjadi satu koleksi
        $allPackages = collect()
            ->merge($packOneday)
            ->merge($packTwoday)
            ->merge($packThreeday)
            ->merge($packFourday);

        return view('agen.booking.all_booking', compact('allPackages'));
    }
}
