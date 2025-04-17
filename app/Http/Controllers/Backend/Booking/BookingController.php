<?php

namespace App\Http\Controllers\Backend\Booking;

use Throwable;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Custom;
use App\Models\Booking;
use App\Models\Facility;
use App\Models\BookingList;
use Illuminate\Http\Request;
use App\Models\PackageOneDay;
use App\Models\PackageTwoDay;
use App\Models\PackageFourDay;
use App\Models\PackageThreeDay;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Notifications\Admin\BookingStatus;
use Illuminate\Support\Facades\Notification;

class BookingController extends Controller
{

    public function Index()
    {
        $agenIds = User::where('role', 'agen')->pluck('id');

        $bookings = Booking::whereHas('bookingList', function ($query) use ($agenIds) {
            $query->whereIn('agen_id', $agenIds);
        })->with(['bookingList', 'bookingList.agen'])->orderBy('created_at', 'desc')->get();

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

        // Gabungkan semua package menjadi satu koleksi
        $allPackages = collect()
            ->merge($packOneday)
            ->merge($packTwoday)
            ->merge($packThreeday)
            ->merge($packFourday);

        return view('agen.booking.all_booking', compact('allPackages'));
    }

    public function SaveBooking(Request $request)
    {
        Log::info('Data request:', $request->all());
        try {
            // Validasi data
            $validated = $request->validate([
                'user_id' => 'required|integer',
                'package_id' => 'required|integer',
                'modalClientName' => 'required|string',
                'modalPackageName' => 'nullable|string',
                'modalStartDate' => 'required|date_format:m/d/Y',
                'modalEndDate' => 'required|date_format:m/d/Y',
                'modalStartTime' => 'sometimes|date_format:H:i',
                'modalEndTime' => 'sometimes|date_format:H:i',
                'modalTotalUser' => 'nullable|integer|min:1',
                'mealStatus' => 'nullable|boolean',
                'modalNote' => 'nullable|string',
                'modalPackageType' => 'nullable|string',
                'modalHotelType' => 'nullable|string', // Untuk package 2-4 hari
            ]);

            if (!$validated) {
                Log::info('Cek Validasi:', $validated);
                return back()->with([
                    'message' => 'Data form belum terisi semua harap di cek kembali',
                    'alert-type' => 'warning',
                ]);
            }

            // Ambil objek User berdasarkan user_id
            $agen = User::find($validated['user_id']);
            if (!$agen) {
                Log::error('Data Agen tidak di temukan', $agen);
                return back()->with([
                    'message' => 'Data agen tidak di temukan',
                    'alert-type' => 'warning',
                ]);
            }

            $packageID = $validated['package_id'];
            $type = $validated['modalPackageType'] ?? null; // Pastikan tipe package ada
            $pricePerPerson = null;
            $totalUser = $validated['modalTotalUser'] ?? 1; // Default 1 jika kosong
            $downPayment = 0;
            $remainingCosts = 0;

            if (!$type) {
                Log::error( 'Tipe package tidak diberikan.', ['validated' => $validated]);
                return back()->with([
                    'message' => 'Tipe package tidak ditemukan.',
                    'alert-type' => 'warning',
                ]);
            }

            if ($type === 'custom') {

                $custom = Custom::where('id', $packageID)->first();

                if (!$custom) {
                    Log::error('Custom package tidak ditemukan.', ['package_id' => $packageID]);
                    return back()->with([
                        'message' => 'Custom package tidak ditemukan.',
                        'alert-type' => 'warning',
                    ]);
                }

                $customPackage = json_decode($custom->custompackage, true);

                if (!is_array($customPackage) || !isset($customPackage['agen_id'])) {
                    Log::error('Data custom package tidak valid atau tidak memiliki agen_id.', ['customPackage' => $customPackage]);
                    return back()->with([
                        'message' => 'Data custom package tidak valid.',
                        'alert-type' => 'error',
                    ]);
                }

                if ($customPackage['agen_id'] != $agen->id) {
                    Log::error('Custom package tidak sesuai dengan Agen.', [
                        'agen_id' => $agen->id,
                        'custom_agen_id' => $customPackage['agen_id']
                    ]);
                    return back()->with([
                        'message' => 'Custom package tidak sesuai dengan Agen',
                        'alert-type' => 'error',
                    ]);
                }

                // Ambil data langsung dari JSON
                $totalUser = $customPackage['participants'];
                $pricePerPerson = $customPackage['costPerPerson'];
                $totalPrice = $customPackage['totalCost'];
                $downPayment = $customPackage['downPayment'];
                $remainingCosts = $customPackage['remainingCosts'];

            }

            // Rent logic
            elseif ($type === 'rent') {
                // Cari rent berdasarkan id
                $rent = Facility::where('id', $packageID)->first();

                if (!$rent) {
                    // Kirim Notification Warning
                    Log::error('Rent tidak ditemukan.', ['package_id' => $packageID]);
                    return back()->with([
                        'message' => 'Rent tidak ditemukan.',
                        'alert-type' => 'warning',
                    ]);
                }

                $totalUser = $validated['modalTotalUser'];

                // Ambil data
                $unitCount = ceil($totalUser / $rent->max_user);
                $totalPrice = $rent->price * $unitCount;
                $pricePerPerson = round($totalPrice / $totalUser);
                $downPayment = 150000 * $unitCount;
                $remainingCosts = $totalPrice - $downPayment;
                $note = $validated['modalNote'];

                Log::info('Grup harga tidak ditemukan.', [
                    'max_user' => $rent->max_user,
                    'harga_perunit' => $rent->price,
                    'unitCount' => $unitCount,
                    'note' => $note,
                    'totalPrice' => $totalPrice,
                    'pricePerPerson' => $pricePerPerson,

                ]);

            } else {
                // Logika untuk package lainnya (twoday, dll)
                $packageModels = [
                    'oneday' => PackageOneDay::class,
                    'twoday' => PackageTwoDay::class,
                    'threeday' => PackageThreeDay::class,
                    'fourday' => PackageFourDay::class,
                ];

                if (!array_key_exists($type, $packageModels)) {
                    // Kirim Notification Warning
                    Log::error('Tipe package tidak valid.', ['type' => $type]);
                    return back()->with([
                        'message' => 'Tipe package tidak valid..',
                        'alert-type' => 'warning',
                    ]);
                }

                $packageModel = $packageModels[$type];
                $package = $packageModel::where('agen_id', $agen->id)
                    ->with(['destinations', 'prices', 'regency'])
                    ->find($packageID);

                if (!$package || !$package->prices) {
                    // Kirim notifikasi error
                    Log::error('Paket tidak ditemukan atau tidak memiliki data harga.', [
                        'package_id' => $packageID,
                        'type' => $type,
                    ]);
                    return back()->with([
                        'message' => 'Paket tidak ditemukan atau tidak memiliki data harga.',
                        'alert-type' => 'error',
                    ]);
                }

                $pricesArray = json_decode($package->prices['price_data'], true);

                if (!is_array($pricesArray)) {
                    // Kirim notifikasi error
                    Log::error('Format data harga tidak valid.', [
                        'package_id' => $packageID,
                        'type' => $type,
                        'price_data' => $package->prices['price_data'],
                    ]);
                    return back()->with([
                        'message' => 'Format data harga tidak valid.',
                        'alert-type' => 'error',
                    ]);
                }

                // Ambil nilai mealStatus, default false jika tidak ada
                $mealStatus = $validated['mealStatus'] ?? false;

                if ($type === 'oneday') {
                    // Ambil harga yang sesuai jumlah user
                    $pricesArray = json_decode($package->prices['price_data'], true);

                    if (!is_array($pricesArray)) {
                        // Kirim notifikasi error
                        Log::error('Format data harga tidak valid.', [
                            'package_id' => $packageID,
                            'type' => $type,
                            'price_data' => $package->prices['price_data'],
                        ]);
                        return back()->with([
                            'message' => 'Format data harga tidak valid.',
                            'alert-type' => 'error',
                        ]);
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

            // Simpan data booking $request->modalNote ?? null,
            $booking = Booking::create([
                'code_booking' => $codeBooking,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'start_trip' => $request->modalStartTime ?? null,
                'end_trip' => $request->modalEndTime ?? null,
                'name' => $validated['modalClientName'],
                'package_name' => $validated['modalPackageName'],
                'note' => $request->modalNote ?? null,
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

            // Redirect ke halaman destinasi dengan notifikasi
            return redirect()->route('all.bookings')->with([
                'message' => 'Booking Package Created Successfully!',
                'alert-type' => 'success',
            ]);

        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Terjadi kesalahan pada StoreBooking.', [
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);

            // Kirim notifikasi error
            return back()->with([
                'message' => 'Terjadi kesalahan pada StoreBooking.!',
                'alert-type' => 'error',
            ]);
        }
    }

    public function EditBooking($id)
    {
        // Cari booking berdasarkan ID atau gagal jika tidak ditemukan
        $booking = Booking::with(['bookingList', 'bookingList.agen'])
            ->whereHas('bookingList', function ($query) {
                $query->whereHas('agen', function ($subQuery) {
                    $subQuery->where('role', 'agen');
                });
            })
            ->findOrFail($id); // Ambil data berdasarkan ID

        $totalUnit = ceil($booking->total_user / 4);
        $rentPrice = $booking->total_price / $totalUnit;

        return view('admin.booking.edit', ['booking' => $booking, 'totalUnit' => $totalUnit, 'rentPrice' => $rentPrice]);
    }

    public function UpdateBooking(Request $request, $id)
    {
        try {


            // Hilangkan titik/koma pada angka sebelum validasi
            $cleanedData = $request->all();
            $numericFields = ['pricePerPerson', 'totalUser', 'totalPrice', 'downPayment', 'remainingCosts'];

            foreach ($numericFields as $field) {
                if ($request->has($field)) {
                    // Hapus titik dan koma, lalu konversi ke integer
                    $cleanedData[$field] = (int) str_replace(['.', ','], '', $request->input($field));
                }
            }

            // Validasi input setelah dibersihkan
            $validatedData = Validator::make($cleanedData, [
                'ClientName' => 'required|string',
                'startDate' => 'required|date_format:Y-m-d',
                'endDate' => 'required|date_format:Y-m-d',
                'startTime' => 'nullable|date_format:H:i',
                'endTime' => 'nullable|date_format:H:i',
                'pricePerPerson' => 'required|numeric',
                'totalUser' => 'required|numeric',
                'totalPrice' => 'required|numeric',
                'downPayment' => 'required|numeric',
                'remainingCosts' => 'required|numeric',
                'noteData' => 'nullable|string',
                'bookingstatus' => 'required|in:pending,booked,paid,finished',
            ])->validate();

            // Cari booking berdasarkan ID
            $booking = Booking::findOrFail($id);

            // Update data booking
            $booking->update([
                'name' => $validatedData['ClientName'],
                'start_date' => $validatedData['startDate'],
                'end_date' => $validatedData['endDate'],
                'start_trip' => $validatedData['startTime'],
                'end_trip' => $validatedData['endTime'],
                'price_person' => $validatedData['pricePerPerson'],
                'total_user' => $validatedData['totalUser'],
                'total_price' => $validatedData['totalPrice'],
                'down_paymet' => $validatedData['downPayment'],
                'remaining_costs' => $validatedData['remainingCosts'],
                'status' => $validatedData['bookingstatus'],
                'note' => $validatedData['noteData'],
            ]);

            // Cari booking berdasarkan ID dan relasi dengan booking_list dan agen
            $bookingAgen = Booking::with(['bookingList', 'bookingList.agen'])
            ->whereHas('bookingList', function ($query) {
                $query->whereHas('agen', function ($subQuery) {
                    $subQuery->where('role', 'agen');
                });
            })->findOrFail($id);

            Notification::send($bookingAgen->bookingList->agen, new BookingStatus($bookingAgen));

            // Kirim notifikasi sukses
            return redirect()->route('all.bookings')->with([
                'message' => 'Booking Data Updated successfully!',
                'alert-type' => 'success',
            ]);

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error in Update Booking:', ['error' => $e->getMessage()]);

            // Kirim notifikasi error
            return back()->with([
                'message' => 'Terjadi kesalahan saat memperbarui data booking!',
                'alert-type' => 'error',
            ]);
        }
    }

    public function getBookings()
    {
        try {
            $bookings = Booking::whereIn('status', ['booked', 'paid'])->get();

            $formattedBookings = $bookings->map(function ($booking) {
                $start = $booking->start_date;
                $end = $booking->end_date;

                // Jika tipe "rent", gabungkan tanggal dengan waktu
                if (strtolower($booking->type) === 'rent') {
                    $start = $booking->start_date . 'T' . $booking->start_trip; // Format ISO 8601 (contoh: 2025-03-01T09:00:00)
                    $end = $booking->end_date . 'T' . $booking->end_trip;
                } else {
                    // Untuk tipe lain, gunakan hanya tanggal (all-day)
                    $end = \Carbon\Carbon::parse($booking->end_date)->addDay()->format('Y-m-d'); // Tambah 1 hari agar FullCalendar menampilkan sampai tanggal akhir
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
                        'client_name' => $booking->name ?? 'N/A', // Tambahkan fallback jika name null
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
        } catch (\Exception $e) {
            Log::error('Error in getBookings: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function DeleteBooking($id)
    {
        try {
            // Cari booking berdasarkan ID
            $booking = Booking::find($id);

            if (!$booking) {
                Log::error('Booking tidak ditemukan', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Data booking tidak ditemukan!'
                ], 404);
            }

            // Cari related booking list (gunakan relasi jika memungkinkan)
            $booklist = BookingList::where('booking_id', $id)->first();

            // Hapus booking list jika ada
            if ($booklist) {
                $booklist->delete();
            }

            // Hapus booking
            $booking->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Error menghapus booking: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => $e
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data'
            ], 500);
        }
    }

    public function markRead($id)
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

    public function markAllRead()
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
