<?php

namespace App\Http\Controllers\Backend\Booking;

use Carbon\Carbon;
use App\Models\User;
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

class BookingController extends Controller
{

    public function Index()
    {
        $agenIds = User::where('role', 'agen')->pluck('id');

        $bookings = Booking::whereHas('bookingList', function ($query) use ($agenIds) {
            $query->whereIn('agen_id', $agenIds);
        })->with(['bookingList', 'bookingList.agen'])->get();

        $pendingStatus = Booking::where('status', 'pending')->count();

        $bookedStatus = Booking::where('status', 'booked')->count();

        $paidStatus = Booking::where('status', 'paid')->count();

        $finishedStatus = Booking::where('status', 'finished')->count();

        return view('admin.booking.index', compact('bookings','pendingStatus','bookedStatus','paidStatus', 'finishedStatus'));
    }

    public function CreateBooking(Request $request, $id)
    {
        $packOneday = PackageOneDay::with(['destinations', 'prices', 'regency'])->get();
        $packTwoday = PackageTwoDay::with(['destinations', 'prices', 'regency'])->get();
        $packThreeday = PackageThreeDay::with(['destinations', 'prices', 'regency'])->get();
        $packFourday = PackageFourDay::with(['destinations', 'prices', 'regency'])->get();

        // Gabungkan semua paket menjadi satu koleksi
        $allPackages = collect()
            ->merge($packOneday)
            ->merge($packTwoday)
            ->merge($packThreeday)
            ->merge($packFourday);

        return view('agen.booking.all_booking', compact('allPackages'));
    }

    public function SaveBooking(Request $request)
    {
        try {
            // Validasi data
            $validated = $request->validate([
                'user_id' => 'required|integer',
                'package_id' => 'required|integer',
                'modalClientName' => 'required|string|max:255',
                'modalStartDate' => 'required|date_format:m/d/Y',
                'modalEndDate' => 'required|date_format:m/d/Y',
                'modalTotalUser' => 'nullable|integer|min:1',
                'mealStatus' => 'nullable|boolean',
                'modalPackageType' => 'nullable|string',
                'modalHotelType' => 'nullable|string', // Untuk paket 2-4 hari
            ]);

            if (!$validated) {
                // Kirim Notification Warning
                $notification = [
                    'message' => 'Data form belum terisi semua harap di cek kembali',
                    'alert-type' => 'warning',
                ];
                Log::info('Cek Validasi:', $validated);
                return back()->with($notification);
            }

            // Ambil objek User berdasarkan user_id
            $agen = User::find($validated['user_id']);
            if (!$agen) {
                // Kirim Notification Warning
                $notification = [
                    'message' => 'Data agen tidak di temukan',
                    'alert-type' => 'warning',
                ];
                Log::error('Data Agen tidak di temukan', $agen);
                return back()->with($notification);
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
                    // Kirim notifikasi error
                    $notification = [
                        'message' => 'Custom package tidak sesuai dengan Agen',
                        'alert-type' => 'error',
                    ];
                    Log::error('Custom package tidak sesuai dengan agen.', [
                        'agen_id' => $agen->id,
                        'custom_agen_id' => $customPackage['agen_id']
                    ]);
                    return back()->with($notification);
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
                    // Kirim notifikasi error
                    $notification = [
                        'message' => 'Paket tidak ditemukan atau tidak memiliki data harga.',
                        'alert-type' => 'error',
                    ];
                    Log::error('Paket tidak ditemukan atau tidak memiliki data harga.', [
                        'package_id' => $packageID,
                        'type' => $type,
                    ]);
                    return back()->with($notification);
                }

                $pricesArray = json_decode($package->prices['price_data'], true);
                if (!is_array($pricesArray)) {
                    // Kirim notifikasi error
                    $notification = [
                        'message' => 'Format data harga tidak valid.',
                        'alert-type' => 'error',
                    ];
                    Log::error('Format data harga tidak valid.', [
                        'package_id' => $packageID,
                        'type' => $type,
                        'price_data' => $package->prices['price_data'],
                    ]);
                    return back()->with($notification);
                }

                // Ambil nilai mealStatus, default false jika tidak ada
                $mealStatus = $validated['mealStatus'] ?? false;

                if ($type === 'oneday') {
                    // Ambil harga yang sesuai jumlah user
                    $pricesArray = json_decode($package->prices['price_data'], true);

                    if (!is_array($pricesArray)) {
                        Log::error('Format data harga tidak valid.', [
                            'package_id' => $packageID,
                            'type' => $type,
                            'price_data' => $package->prices['price_data'],
                        ]);
                        return back()->withErrors(['error' => 'Format data harga tidak valid.']);
                    }

                    // Gunakan filter untuk mencari data harga sesuai jumlah user
                    $priceData = collect($pricesArray)->firstWhere('user', (int)$totalUser);

                    if (!$priceData) {
                        Log::error('Harga untuk jumlah user tidak ditemukan.', [
                            'package_id' => $packageID,
                            'type' => $type,
                            'total_user' => $totalUser,
                        ]);
                        return back()->withErrors(['error' => 'Harga untuk jumlah user tidak ditemukan.']);
                    }

                    // Ambil harga berdasarkan mealStatus
                    if ($mealStatus) {
                        $pricePerPerson = isset($priceData['nomeal']) ? $priceData['nomeal'] : null;
                    } else {
                        $pricePerPerson = isset($priceData['price']) ? $priceData['price'] : null;
                    }

                    if (!$pricePerPerson || !is_numeric($pricePerPerson)) {
                        Log::error('Harga tidak ditemukan atau tidak valid.', [
                            'package_id' => $packageID,
                            'type' => $type,
                            'total_user' => $totalUser,
                            'mealStatus' => $mealStatus,
                        ]);
                        return back()->withErrors(['error' => 'Harga tidak ditemukan atau tidak valid.']);
                    }
                } else {
                    // Logika untuk twoday, threeday, fourday
                    $hotelType = $validated['modalHotelType'] ?? null;
                    if (!$hotelType) {
                        Log::error('Tipe hotel tidak diberikan.', [
                            'package_id' => $packageID,
                            'type' => $type,
                        ]);
                        return back()->withErrors(['error' => 'Tipe hotel tidak diberikan.']);
                    }

                    // Tentukan grup harga berdasarkan mealStatus
                    $priceType = $mealStatus ? 'Include Meal' : 'Exclude Meal';
                    $selectedGroup = collect($pricesArray)->firstWhere('Price Type', $priceType);
                    if (!$selectedGroup) {
                        // Kirim notifikasi error
                        $notification = [
                            'message' => 'Grup harga tidak ditemukan.',
                            'alert-type' => 'error',
                        ];
                        Log::error('Grup harga tidak ditemukan.', [
                            'package_id' => $packageID,
                            'type' => $type,
                            'price_type' => $priceType,
                        ]);
                        return back()->with($notification);
                    }

                    // Cari harga berdasarkan user dan hotelType
                    $groupData = $selectedGroup['data'];
                    $priceData = collect($groupData)->firstWhere('user', (int)$totalUser);
                    if (!$priceData || !isset($priceData[$hotelType])) {
                        // Kirim notifikasi error
                        $notification = [
                            'message' => 'Harga untuk tipe hotel tidak ditemukan.',
                            'alert-type' => 'error',
                        ];
                        Log::error('Harga untuk tipe hotel tidak ditemukan.', [
                            'package_id' => $packageID,
                            'type' => $type,
                            'total_user' => $totalUser,
                            'hotel_type' => $hotelType,
                        ]);
                        return back()->with($notification);
                    }

                    $pricePerPerson = $priceData[$hotelType];
                }

                if (!$pricePerPerson || !is_numeric($pricePerPerson)) {
                    // Kirim notifikasi error
                    $notification = [
                        'message' => 'Harga tidak ditemukan atau tidak valid.',
                        'alert-type' => 'error',
                    ];
                    Log::error('Harga tidak ditemukan atau tidak valid.', [
                        'package_id' => $packageID,
                        'type' => $type,
                    ]);
                    return back()->with($notification);
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
            return redirect()->route('all.bookings')->with($notification);

        } catch (\Exception $e) {
            // Kirim notifikasi error
            $notification = [
                'message' => 'Terjadi kesalahan pada StoreBooking.!',
                'alert-type' => 'error',
            ];
            // Log error jika terjadi masalah
            Log::error('Terjadi kesalahan pada StoreBooking.', [
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            return back()->with($notification);
        }
    }
}
