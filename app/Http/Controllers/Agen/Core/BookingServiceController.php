<?php

namespace App\Http\Controllers\Agen\Core;


use Carbon\Carbon;
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

    public function StoreBooking(Request $request)
    {
        try {
            $agen = Auth::user();

            // Menggabungkan semua paket berdasarkan agen
            $packOneday = PackageOneDay::where('agen_id', $agen->id)
                ->with(['destinations', 'prices', 'regency'])->get();
            $packTwoday = PackageTwoDay::where('agen_id', $agen->id)
                ->with(['destinations', 'prices', 'regency'])->get();
            $packThreeday = PackageThreeDay::where('agen_id', $agen->id)
                ->with(['destinations', 'prices', 'regency'])->get();
            $packFourday = PackageFourDay::where('agen_id', $agen->id)
                ->with(['destinations', 'prices', 'regency'])->get();

            $allPackages = collect()
                ->merge($packOneday)
                ->merge($packTwoday)
                ->merge($packThreeday)
                ->merge($packFourday);

            // Validasi data
            $validated = $request->validate([
                'package_id' => 'required|integer',
                'modalClientName' => 'required|string|max:255',
                'modalStartDate' => 'required|date_format:m/d/Y',
                'modalEndDate' => 'required|date_format:m/d/Y',
                'modalTotalUser' => 'required|integer|min:1',
                'modalHotelType' => 'nullable|string', // Untuk paket 2-4 hari
            ]);

            // Cari paket berdasarkan ID
            $selectedPackage = $allPackages->firstWhere('id', $validated['package_id']);

            if (!$selectedPackage) {
                Log::error('Paket tidak ditemukan.', ['package_id' => $validated['package_id']]);
                return back()->withErrors(['error' => 'Paket tidak ditemukan.']);
            }

            $type = $selectedPackage->type;
            $pricePerPerson = null;

            // Cek tipe paket dan tentukan harga
            if ($type === 'oneday') {
                // Decode JSON data
                $pricesArray = json_decode($selectedPackage->prices['price_data'], true);

                // Cari data harga berdasarkan jumlah pengguna
                $priceData = collect($pricesArray)->firstWhere('user', (int)$validated['modalTotalUser']);

                // Ambil harga per orang
                $pricePerPerson = $priceData['price'];

            } elseif (in_array($type, ['twoday', 'threeday', 'fourday'])) {
                $pricesArray = json_decode($selectedPackage->prices['price_data'], true);

                $priceData = collect($pricesArray)->firstWhere('user', (int)$validated['modalTotalUser']);

                $hotelType = $validated['modalHotelType'];

                $pricePerPerson = $priceData[$hotelType];
            } else {
                Log::error('Tipe paket tidak valid.', ['type' => $type]);
                return back()->withErrors(['error' => 'Tipe paket tidak valid.']);
            }

            // Hitung harga total
            $totalPrice = $pricePerPerson * $validated['modalTotalUser'];

            // Buat kode booking unik
            $codeBooking = 'BOOK-' . strtoupper(uniqid());

            $startDate = Carbon::createFromFormat('m/d/Y', $validated['modalStartDate'])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('m/d/Y', $validated['modalEndDate'])->format('Y-m-d');

            // Simpan data booking
            $booking = Booking::create([
                'code_booking' => $codeBooking,
                'start_date' => $startDate,
                'end_date' => $endDate ,
                'name' => $validated['modalClientName'],
                'type' => $type,
                'total_user' => $validated['modalTotalUser'],
                'price_person' => $pricePerPerson,
                'total_price' => $totalPrice,
                'down_paymet' => $totalPrice * 0.3, // 30% DP
                'remaining_costs' => $totalPrice * 0.7,
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




}
