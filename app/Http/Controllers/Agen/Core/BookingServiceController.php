<?php

namespace App\Http\Controllers\Agen\Core;


use Carbon\Carbon;
use App\Models\Custom;
use App\Models\Booking;
use App\Models\BookingList;
use Illuminate\Http\Request;
use App\Models\PackageOneDay;
use App\Models\PackageTwoDay;
use App\Models\PackageFourDay;
use App\Models\PackageThreeDay;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BookingServiceController extends Controller
{
    public function AllBooking()
    {
        $agen = Auth::user();

        // Ambil semua data booking berdasarkan agen_id
        $bookings = Booking::whereHas('bookingList', function ($query) use ($agen) {
            $query->where('agen_id', $agen->id);
        })->get();

        $pendingStatus = Booking::whereHas('bookingList', function ($query) use ($agen) {
            $query->where('agen_id', $agen->id);
        })->where('status', 'pending')->count();

        $bookedStatus = Booking::whereHas('bookingList', function ($query) use ($agen) {
            $query->where('agen_id', $agen->id);
        })->where('status', 'booked')->count();

        $paidStatus = Booking::whereHas('bookingList', function ($query) use ($agen) {
            $query->where('agen_id', $agen->id);
        })->where('status', 'paid')->count();

        $finishedStatus = Booking::whereHas('bookingList', function ($query) use ($agen) {
            $query->where('agen_id', $agen->id);
        })->where('status', 'finished')->count();

        return view('agen.booking.all_booking', compact('bookings', 'pendingStatus', 'bookedStatus', 'paidStatus', 'finishedStatus'));
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

    public function StoreBooking(Request $request)
    {
        try {
            // Validasi data
            $validated = $request->validate([
                'package_id' => 'required|integer',
                'modalClientName' => 'required|string|max:255',
                'modalStartDate' => 'required|date_format:m/d/Y',
                'modalEndDate' => 'required|date_format:m/d/Y',
                'modalTotalUser' => 'nullable|integer|min:1',
                'modalPackageType' => 'nullable|string',
                'modalHotelType' => 'nullable|string', // Untuk paket 2-4 hari
            ]);

            Log::info('Cek Validasi:', $validated);

            // Mendapatkan data agen (user yang sedang login)
            $agen = Auth::user();
            if (!$agen) {
                abort(403, 'Agen tidak ditemukan.');
            }

            $packageID = $validated['package_id'];
            $type = $validated['modalPackageType'] ?? null; // Pastikan tipe paket ada
            $pricePerPerson = null;
            $totalUser = $validated['modalTotalUser'] ?? 1; // Default 1 jika kosong
            $downPayment = 0;
            $remainingCosts = 0;

            if (!$type) {
                Log::error('Tipe paket tidak diberikan.', ['validated' => $validated]);
                return back()->withErrors(['error' => 'Tipe paket tidak diberikan.']);
            }

            if ($type === 'custom') {
                // Cari custom package berdasarkan id
                $custom = Custom::where('id', $packageID)->first();

                if (!$custom) {
                    Log::error('Custom package tidak ditemukan.', ['package_id' => $packageID]);
                    return back()->withErrors(['error' => 'Custom package tidak ditemukan.']);
                }

                $customPackage = json_decode($custom->custompackage, true);

                // Pastikan agen_id di JSON cocok dengan agen saat ini
                if ($customPackage['agen_id'] != $agen->id) {
                    Log::error('Custom package tidak sesuai dengan agen.', [
                        'agen_id' => $agen->id,
                        'custom_agen_id' => $customPackage['agen_id']
                    ]);
                    return back()->withErrors(['error' => 'Custom package tidak sesuai dengan agen.']);
                }

                // Ambil data langsung dari JSON
                $totalUser = $customPackage['participants'];
                $pricePerPerson = $customPackage['costPerPerson'];
                $totalPrice = $customPackage['totalCost'];
                $downPayment = $customPackage['downPayment'];
                $remainingCosts = $customPackage['remainingCosts'];

            } else {
                // Logika untuk paket lainnya (oneday, twoday, dll)
                $packageModels = [
                    'oneday' => PackageOneDay::class,
                    'twoday' => PackageTwoDay::class,
                    'threeday' => PackageThreeDay::class,
                    'fourday' => PackageFourDay::class,
                ];

                if (!array_key_exists($type, $packageModels)) {
                    Log::error('Tipe paket tidak valid.', ['type' => $type]);
                    return back()->withErrors(['error' => 'Tipe paket tidak valid.']);
                }

                $packageModel = $packageModels[$type];
                $package = $packageModel::where('agen_id', $agen->id)
                    ->with(['destinations', 'prices', 'regency'])
                    ->find($packageID);

                if (!$package || !$package->prices) {
                    Log::error('Paket tidak ditemukan atau tidak memiliki data harga.', [
                        'package_id' => $packageID,
                        'type' => $type,
                    ]);
                    return back()->withErrors(['error' => 'Paket tidak ditemukan atau tidak memiliki data harga.']);
                }

                $pricesArray = json_decode($package->prices['price_data'], true);
                if (!is_array($pricesArray)) {
                    Log::error('Format data harga tidak valid.', [
                        'package_id' => $packageID,
                        'type' => $type,
                        'price_data' => $package->prices['price_data'],
                    ]);
                    return back()->withErrors(['error' => 'Data harga tidak valid.']);
                }

                $priceData = collect($pricesArray)->firstWhere('user', (int)$totalUser);
                if (!$priceData) {
                    Log::error('Harga untuk jumlah user tidak ditemukan.', [
                        'package_id' => $packageID,
                        'type' => $type,
                        'total_user' => $totalUser,
                    ]);
                    return back()->withErrors(['error' => 'Harga untuk jumlah user tidak ditemukan.']);
                }

                if (in_array($type, ['twoday', 'threeday', 'fourday'])) {
                    $hotelType = $validated['modalHotelType'] ?? null;
                    if (!$hotelType || !isset($priceData[$hotelType])) {
                        Log::error('Harga berdasarkan tipe hotel tidak ditemukan.', [
                            'package_id' => $packageID,
                            'type' => $type,
                            'total_user' => $totalUser,
                            'hotel_type' => $hotelType,
                        ]);
                        return back()->withErrors(['error' => 'Harga berdasarkan tipe hotel tidak ditemukan.']);
                    }

                    $pricePerPerson = $priceData[$hotelType];
                } else {
                    $pricePerPerson = $priceData['price'] ?? null;
                }

                if (!$pricePerPerson || !is_numeric($pricePerPerson)) {
                    Log::error('Harga tidak ditemukan atau tidak valid.', [
                        'package_id' => $packageID,
                        'type' => $type,
                    ]);
                    return back()->withErrors(['error' => 'Harga tidak ditemukan atau tidak valid.']);
                }

                $totalPrice = $pricePerPerson * $totalUser;
                $downPayment = $totalPrice * 0.3; // 30% DP
                $remainingCosts = $totalPrice * 0.7;
            }

            // Buat kode booking unik
            $codeBooking = 'BOOK-' . strtoupper(uniqid());

            $startDate = Carbon::createFromFormat('m/d/Y', $validated['modalStartDate'])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('m/d/Y', $validated['modalEndDate'])->format('Y-m-d');

            // Simpan data booking
            $booking = Booking::create([
                'code_booking' => $codeBooking,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'name' => $validated['modalClientName'],
                'type' => $type,
                'total_user' => $totalUser,
                'price_person' => $pricePerPerson,
                'total_price' => $totalPrice,
                'down_paymet' => $downPayment,
                'remaining_costs' => $remainingCosts,
                'status' => 'pending',
            ]);

            // Simpan ke booking_list
            $bookingList = BookingList::create([
                'booking_id' => $booking->id,
                'agen_id' => $agen->id,
                'package_id' => $validated['package_id'],
            ]);

            // Update booking_list_id pada tabel bookings
            $booking->update(['booking_list_id' => $bookingList->id]);

            // Kirim notifikasi berhasil
            $notification = [
                'message' => 'Booking Package Created Successfully!',
                'alert-type' => 'success',
            ];

            // Redirect ke halaman destinasi dengan notifikasi
            return redirect()->route('agen.booking')->with($notification);

        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Terjadi kesalahan pada StoreBooking.', [
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'Terjadi kesalahan, silakan coba lagi.']);
        }
    }

    // private function getPackagePrice($type, $agenId, $packageID, $totalUser, $hotelType = null)
    // {
    //     Log::info('Start getPackagePrice :');

    //     $packageModels = [
    //         'oneday' => PackageOneDay::class,
    //         'twoday' => PackageTwoDay::class,
    //         'threeday' => PackageThreeDay::class,
    //         'fourday' => PackageFourDay::class,
    //     ];

    //     // Validasi tipe paket
    //     if (!array_key_exists($type, $packageModels)) {
    //         Log::error('Tipe paket tidak valid.', ['type' => $type]);
    //         abort(404, 'Tipe paket tidak valid.');
    //     }

    //     // Ambil model paket sesuai tipe
    //     $packageModel = $packageModels[$type];
    //     $package = $packageModel::where('agen_id', $agenId)
    //         ->with(['destinations', 'prices', 'regency'])
    //         ->find($packageID);

    //     // Validasi jika paket tidak ditemukan
    //     if (!$package || !$package->prices) {
    //         Log::error('Paket tidak ditemukan atau tidak memiliki data harga.', [
    //             'package_id' => $packageID,
    //             'type' => $type,
    //         ]);
    //         abort(404, 'Paket tidak ditemukan atau tidak memiliki data harga.');
    //     }

    //     // Decode harga dari kolom JSON
    //     $pricesArray = json_decode($package->prices['price_data'], true);
    //     if (!is_array($pricesArray)) {
    //         Log::error('Format data harga tidak valid.', [
    //             'package_id' => $packageID,
    //             'type' => $type,
    //             'price_data' => $package->prices['price_data'],
    //         ]);
    //         abort(500, 'Data harga tidak valid.');
    //     }

    //     // Cari harga berdasarkan jumlah user
    //     $priceData = collect($pricesArray)->firstWhere('user', (int)$totalUser);
    //     if (!$priceData) {
    //         Log::error('Harga untuk jumlah user tidak ditemukan.', [
    //             'package_id' => $packageID,
    //             'type' => $type,
    //             'total_user' => $totalUser,
    //         ]);
    //         abort(404, 'Harga untuk jumlah user tidak ditemukan.');
    //     }

    //     // Untuk tipe paket multi-day (twoday, threeday, fourday), harga tergantung hotelType
    //     if (in_array($type, ['twoday', 'threeday', 'fourday'])) {
    //         if (!$hotelType || !isset($priceData[$hotelType])) {
    //             Log::error('Harga berdasarkan tipe hotel tidak ditemukan.', [
    //                 'package_id' => $packageID,
    //                 'type' => $type,
    //                 'total_user' => $totalUser,
    //                 'hotel_type' => $hotelType,
    //             ]);
    //             abort(404, 'Harga berdasarkan tipe hotel tidak ditemukan.');
    //         }

    //         return $priceData[$hotelType];
    //     }

    //     // Untuk tipe oneday, cukup ambil harga langsung
    //     return $priceData['price'] ?? null;
    // }

    public function getBookingDetails($id)
    {
        // Ambil data Booking dengan relasi bookingList
        $booking = Booking::with('bookingList')->find($id);

        if (!$booking) {
            return response()->json(['html' => '<p class="text-center text-danger">Data booking tidak ditemukan.</p>']);
        }

        // Render view partial_booking_details.blade.php dengan data booking
        $html = view('agen.booking.partial_booking_details', compact('booking'))->render();

        return response()->json(['html' => $html]);
    }




}
