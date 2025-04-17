<?php

namespace App\Http\Controllers\Agen\Core;


use Throwable;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Agen\AgenBookingCreated;

class BookingServiceController extends Controller
{
    public function AllBooking()
    {
        $agen = Auth::user();

        // Ambil semua data booking berdasarkan agen_id
        $bookings = Booking::whereHas('bookingList', function ($query) use ($agen) {
            $query->where('agen_id', $agen->id);
        })->orderBy('created_at', 'desc')->get();

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

        return view('agen.booking.all_booking', compact('bookings', 'pendingStatus', 'bookedStatus', 'paidStatus', 'finishedStatus', 'agen'));
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
            // Ambil data admin
            $admin = User::where('role', 'admin')->first();

            // Validasi data
            $validated = $request->validate([
                'package_id' => 'required|integer',
                'modalClientName' => 'required|string|max:255',
                'modalPackageName' => 'required|string|max:255',
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

            // Mendapatkan data agen (user yang sedang login)
            $agen = Auth::user();
            if (!$agen) {
                // Kirim Notification Warning
                $notification = [
                    'message' => 'Data tidak di temukan',
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
                // Kirim Notification Warning
                $notification = [
                    'message' => 'Tipe paket tidak ditemukan.',
                    'alert-type' => 'warning',
                ];
                Log::error('Tipe paket tidak diberikan.', ['validated' => $validated]);
                return back()->with($notification);
            }

            if ($type === 'custom') {
                // Cari custom package berdasarkan id
                $custom = Custom::where('id', $packageID)->first();

                if (!$custom) {
                    // Kirim Notification Warning
                    $notification = [
                        'message' => 'Custom package tidak ditemukan.',
                        'alert-type' => 'warning',
                    ];
                    Log::error('Custom package tidak ditemukan.', ['package_id' => $packageID]);
                    return back()->with($notification);
                }

                $customPackage = json_decode($custom->custompackage, true);

                // Pastikan agen_id di JSON cocok dengan agen saat ini
                if ($customPackage['agen_id'] != $agen->id) {
                    // Kirim Notification Warning
                    $notification = [
                        'message' => 'Custom package tidak sesuai dengan agen.',
                        'alert-type' => 'warning',
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
                    // Kirim Notification Warning
                    $notification = [
                        'message' => 'Tipe paket tidak valid..',
                        'alert-type' => 'warning',
                    ];
                    Log::error('Tipe paket tidak valid.', ['type' => $type]);
                    return back()->with($notification);
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

                    // Gunakan filter untuk mencari data harga sesuai jumlah user
                    $priceData = collect($pricesArray)->firstWhere('user', (int)$totalUser);

                    if (!$priceData) {
                        // Kirim notifikasi error
                        $notification = [
                            'message' => 'Harga untuk jumlah user tidak ditemukan.',
                            'alert-type' => 'error',
                        ];
                        Log::error('Harga untuk jumlah user tidak ditemukan.', [
                            'package_id' => $packageID,
                            'type' => $type,
                            'total_user' => $totalUser,
                        ]);
                        return back()->with($notification);
                    }

                    // Ambil harga berdasarkan mealStatus
                    if ($mealStatus) {
                        $pricePerPerson = isset($priceData['price']) ? $priceData['price'] : null;
                    } else {
                        $pricePerPerson = isset($priceData['nomeal']) ? $priceData['nomeal'] : null;
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
                            'total_user' => $totalUser,
                            'mealStatus' => $mealStatus,
                        ]);
                        return back()->with($notification);
                    }
                } else {
                    // Logika untuk twoday, threeday, fourday
                    $hotelType = $validated['modalHotelType'] ?? null;
                    if (!$hotelType) {
                        // Kirim notifikasi error
                        $notification = [
                            'message' => 'Tipe hotel tidak ditemukan.',
                            'alert-type' => 'error',
                        ];
                        Log::error('Tipe hotel tidak diberikan.', [
                            'package_id' => $packageID,
                            'type' => $type,
                        ]);
                        return back()->with($notification);
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
                'package_name' => $validated['modalPackageName'],
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

            Notification::send($admin, new AgenBookingCreated($agen->username));

            // Kirim Notifikasi dan Redirect ke halaman destinasi dengan notifikasi
            return redirect()->route('agen.booking')->with([
                'message' => 'Booking Package Created Successfully!',
                'alert-type' => 'success',
            ]);
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

    public function getBookings()
    {
        // Mendapatkan data agen yang sedang login
        $agen = Auth::user();

        // Periksa apakah agen berhasil login
        if (!$agen) {
            Log::error('Agen not authenticated in getBookings.');
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        try {
            // Mengambil semua data booking yang terkait dengan agen yang sedang login
            $bookings = Booking::whereHas('bookingList', function ($query) use ($agen) {
                    $query->where('agen_id', $agen->id);
                })
                ->with('bookingList') // Eager load relasi bookingList
                ->whereIn('status', ['booked', 'paid']) // Sesuai dengan filter status sebelumnya
                ->get();

            // Format data untuk FullCalendar
            $formattedBookings = $bookings->map(function ($booking) {
                $start = $booking->start_date;
                $end = $booking->end_date;

                // Jika tipe "rent", gabungkan tanggal dengan waktu
                if (strtolower($booking->type) === 'rent') {
                    $start = $booking->start_date . 'T' . ($booking->start_trip ?? '00:00'); // Format ISO 8601
                    $end = $booking->end_date . 'T' . ($booking->end_trip ?? '00:00');
                } else {
                    // Untuk tipe lain, gunakan hanya tanggal (all-day)
                    $end = \Carbon\Carbon::parse($booking->end_date)->addDay()->format('Y-m-d');
                }

                return [
                    'id' => $booking->id,
                    'title' => $booking->code_booking,
                    'start' => $start,
                    'end' => $end,
                    'allDay' => strtolower($booking->type) !== 'rent', // allDay false untuk rent, true untuk lainnya
                    'extendedProps' => [
                        'code_booking' => $booking->code_booking,
                        'agen_name' => $booking->bookingList->agen->username ?? 'N/A',
                        'type' => $booking->type,
                        'status' => $booking->status,
                        'client_name' => $booking->name ?? 'N/A',
                        'start_date' => $booking->start_date,
                        'end_date' => $booking->end_date,
                        'start_trip' => $booking->start_trip,
                        'end_trip' => $booking->end_trip,
                        'price_person' => $booking->price_person,
                        'total_user' => $booking->total_user,
                        'total_price' => $booking->total_price,
                        'down_payment' => $booking->down_paymet,
                        'remaining_costs' => $booking->remaining_costs,
                    ],
                ];
            });

            return response()->json($formattedBookings);
        } catch (\Throwable $e) {
            Log::error('Error in getBookings: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve bookings.'], 500);
        }
    }

    public function markAsRead(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $notification = $user->notifications()->find($id); // Gunakan find() saja, handle jika null

            if (!$notification) {
                return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan.'], 404);
            }

            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'count' => $user->unreadNotifications()->count()
            ]);
        } catch (Throwable $e) {
            report($e); // Log error untuk debugging

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() || 'Terjadi kesalahan server.'
            ], 500);
        }
    }

    public function markAllAsRead()
    {
        // Ambil semua notifikasi yang belum dibaca untuk user yang sedang login
        $user = Auth::user();

        if ($user) {
            $user->unreadNotifications->markAsRead(); // Tandai semua sebagai telah dibaca
            return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
        }

        return response()->json(['success' => false, 'message' => 'User not authenticated.'], 401);
    }
}
