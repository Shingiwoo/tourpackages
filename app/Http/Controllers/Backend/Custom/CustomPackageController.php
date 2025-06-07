<?php

namespace App\Http\Controllers\Backend\Custom;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Custom;
use App\Models\Regency;
use App\Models\Vehicle;
use App\Models\Facility;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Services\Calculate\CustomPackageCalculatorService;

class CustomPackageController extends Controller
{
    protected $calculatorService;

    // Injeksi Service melalui constructor
    public function __construct(CustomPackageCalculatorService $calculatorService)
    {
        $this->calculatorService = $calculatorService;
    }

    public function CustomDashboard()
    {
        $destinations = Destination::all();
        $regencies = Regency::all();
        $vehicles = Vehicle::all();
        $facilities = Facility::all();
        $allagens = User::where('role', 'agen')->get();
        $hotels = Hotel::active()->get();

        $customPackage = Custom::first(); 
        $prices = $customPackage ? json_decode($customPackage->custompackage, true) : null;

        return view('admin.custom.calculate', compact('destinations', 'regencies', 'vehicles', 'facilities', 'prices', 'allagens', 'hotels'));
    }


    public function StoreData(Request $request)
    {
        try {
            Log::info('Mulai Menghitung Custom Paket!');

            // --- Validasi awal untuk SEMUA input yang mungkin ada ---
            // Input yang bisa di-disabled harus tetap 'nullable' di sini.
            $request->validate([
                'facilities' => 'required|array',
                'facilities.*' => 'exists:facilities,id',
                'destinations' => 'required|array',
                'destinations.*' => 'exists:destinations,id',
                'DurationPackage' => 'required|integer|min:1',
                'vehicleName' => 'required|exists:vehicles,id',
                'totalUser' => 'required|integer|min:1',

                // Field Meal (bisa disabled, jadi nullable)
                'mealCost' => 'nullable',
                'totalMeal' => 'nullable|integer|min:1',

                // Field Hotel Manual (bisa disabled, jadi nullable)
                'hotelPrice' => 'nullable',
                'night' => 'nullable|integer|min:0',
                'capacityHotel' => 'nullable|integer|min:1',
                'extraBedPrice' => 'nullable', 

                // Field Hotel Advanced (bisa disabled, jadi nullable)
                'selectedHotels' => 'nullable|array',
                'selectedHotels.*' => 'exists:hotels,id',
                'advancedExtraBedPrice' => 'nullable',
                'nightAdvanced' => 'nullable|integer|min:0', 

                'otherFee' => 'required',
                'reservedFee' => 'required',
            ]);

            // Setelah validasi dasar, ambil semua input
            $inputData = $request->all();
            Log::info('Validasi dasar berhasil. Input mentah:', $inputData);

            // --- TENTUKAN status IncludeMeal ---
            $includeMeal = $request->has('mealCost'); // Jika mealCost terkirim, berarti include meal
            Log::debug('Status IncludeMeal:', ['status' => $includeMeal]);

            // --- TENTUKAN accommodationType BERDASARKAN INPUT YANG DITERIMA ---
            $accommodationType = 'none'; 
            $hasHotelPrice = $request->has('hotelPrice') && !empty($request->input('hotelPrice'));
            $hasSelectedHotels = $request->has('selectedHotels') && is_array($request->input('selectedHotels')) && !empty($request->input('selectedHotels'));

            if ($hasHotelPrice) {
                $accommodationType = 'manual';
            } elseif ($hasSelectedHotels) {
                $accommodationType = 'advanced';
            }
            Log::info('Accommodation Type terdeteksi:', ['type' => $accommodationType]);


            // --- LAKUKAN VALIDASI KONDISIONAL SECARA MANUAL ---
            $errors = [];

            // Validasi untuk Meal jika diaktifkan
            if ($includeMeal) {
                if (!isset($inputData['mealCost']) || $inputData['mealCost'] === '' || $inputData['mealCost'] === null) {
                    $errors['mealCost'] = 'Biaya Makan per orang wajib diisi.';
                }
                if (!isset($inputData['totalMeal']) || $inputData['totalMeal'] === '' || $inputData['totalMeal'] === null) {
                    $errors['totalMeal'] = 'Jumlah Makan per hari wajib diisi.';
                }
            } else {
                // Jika tidak include meal, pastikan nilai default untuk perhitungan
                $inputData['mealCost'] = 0;
                $inputData['totalMeal'] = 0;
            }

            // Validasi untuk accommodationType manual
            if ($accommodationType === 'manual') {
                if (!isset($inputData['hotelPrice']) || $inputData['hotelPrice'] === '' || $inputData['hotelPrice'] === null) {
                    $errors['hotelPrice'] = 'Harga Hotel wajib diisi untuk pilihan Manual Hotel.';
                }
                if (!isset($inputData['night']) || $inputData['night'] === '' || $inputData['night'] === null) {
                    $errors['night'] = 'Jumlah Malam wajib diisi untuk pilihan Manual Hotel.';
                }
                if (!isset($inputData['capacityHotel']) || $inputData['capacityHotel'] === '' || $inputData['capacityHotel'] === null) {
                    $errors['capacityHotel'] = 'Kapasitas Kamar wajib diisi untuk pilihan Manual Hotel.';
                }
                if (!isset($inputData['extraBedPrice']) || $inputData['extraBedPrice'] === '' || $inputData['extraBedPrice'] === null) {
                    $errors['extraBedPrice'] = 'Harga Extra Bed wajib diisi untuk pilihan Manual Hotel.';
                }
            }
            // Validasi untuk accommodationType advanced
            elseif ($accommodationType === 'advanced') {
                if (!isset($inputData['selectedHotels']) || !is_array($inputData['selectedHotels']) || empty($inputData['selectedHotels'])) {
                    $errors['selectedHotels'] = 'Pilih setidaknya satu Hotel untuk pilihan Select Hotel.';
                }
                // --- PERUBAHAN DI SINI UNTUK NAMA INPUT BARU ---
                if (!isset($inputData['nightAdvanced']) || $inputData['nightAdvanced'] === '' || $inputData['nightAdvanced'] === null) {
                    $errors['nightAdvanced'] = 'Jumlah Malam wajib diisi untuk pilihan Select Hotel.';
                }
                if (!isset($inputData['advancedExtraBedPrice']) || $inputData['advancedExtraBedPrice'] === '' || $inputData['advancedExtraBedPrice'] === null) {
                    $errors['advancedExtraBedPrice'] = 'Harga Extra Bed wajib diisi untuk pilihan Select Hotel.';
                }
            } else { // accommodationType === 'none' (tidak ada hotel dipilih)
                // Pastikan nilai default untuk perhitungan
                $inputData['hotelPrice'] = 0;
                $inputData['extraBedPrice'] = 0;
                $inputData['night'] = 0; 
                $inputData['capacityHotel'] = 1;
                $inputData['selectedHotels'] = [];
            }

            // Jika ada error dari validasi manual, lemparkan ValidationException
            if (!empty($errors)) {
                $validator = Validator::make([], []);
                foreach ($errors as $field => $message) {
                    $validator->errors()->add($field, $message);
                }
                throw new ValidationException($validator);
            }

            // Bersihkan nilai numerik dari inputData
            try {
                $cleanedData = [
                    'otherFee' => $this->cleanNumericValue($inputData['otherFee']),
                    'reservedFee' => $this->cleanNumericValue($inputData['reservedFee']),
                    'mealCost' => $this->cleanNumericValue($inputData['mealCost']),
                    'totalMeal' => (int)($inputData['totalMeal'] ?? 0),

                    // --- PERUBAHAN DI SINI UNTUK PENGAMBILAN NILAI YANG BENAR ---
                    // Default 0 jika manual tidak aktif
                    'hotelPrice' => $this->cleanNumericValue($inputData['hotelPrice'] ?? 0),

                    // Ambil dari salah satu
                    'extraBedPrice' => $this->cleanNumericValue($inputData['extraBedPrice'] ?? $inputData['advancedExtraBedPrice'] ?? 0),

                    // Ambil dari salah satu 
                    'night' => (int)($inputData['night'] ?? $inputData['nightAdvanced'] ?? 0),
                    'capacityHotel' => (int)($inputData['capacityHotel'] ?? 1),
                ];

                Log::debug('Nilai setelah dibersihkan:', $cleanedData);
            } catch (\Exception $e) {
                Log::error('Gagal membersihkan nilai numerik: ' . $e->getMessage());
                throw new \Exception('Format nilai tidak valid: ' . $e->getMessage());
            }

            // Siapkan data untuk service
            $calculationData = [
                'destinationIds' => $inputData['destinations'],
                'facilityIds' => $inputData['facilities'],
                'vehicleId' => (int)$inputData['vehicleName'],
                'DurationPackage' => (int)$inputData['DurationPackage'],
                'participants' => (int)$inputData['totalUser'],
                'night' => $cleanedData['night'],
                'mealCost' => $cleanedData['mealCost'],
                'totalMeal' => $cleanedData['totalMeal'],
                'otherFee' => $cleanedData['otherFee'],
                'reservedFee' => $cleanedData['reservedFee'],
                'accommodationType' => $accommodationType,
                'hotelPrice' => $cleanedData['hotelPrice'],
                'selectedHotels' => $inputData['selectedHotels'] ?? [],
                'extraBedPrice' => $cleanedData['extraBedPrice'],
                'capacityHotel' => $cleanedData['capacityHotel'],
            ];

            Log::info('Data untuk kalkulasi:', $calculationData);

            // Hitung harga menggunakan service
            try {
                $prices = $this->calculatorService->calculate($calculationData);
                Log::info('Hasil kalkulasi:', $prices);
            } catch (\Exception $e) {
                Log::error('Gagal melakukan kalkulasi: ' . $e->getMessage());
                throw new \Exception('Terjadi kesalahan dalam perhitungan: ' . $e->getMessage());
            }

            // Simpan data ke tabel customs
            try {
                $customData = Custom::first();
                if ($customData) {
                    $customData->update(['custompackage' => json_encode($prices)]);
                    Log::info('Data custom diperbarui', ['id' => $customData->id]);
                } else {
                    $customData = Custom::create(['custompackage' => json_encode($prices)]);
                    Log::info('Data custom dibuat baru', ['id' => $customData->id]);
                }
            } catch (\Exception $e) {
                Log::error('Gagal menyimpan data custom: ' . $e->getMessage());
                throw new \Exception('Gagal menyimpan hasil perhitungan');
            }

            return redirect()->route('calculate.custom.package')->with([
                'message' => 'Calculations Successfully!',
                'alert-type' => 'success'
            ]);

        } catch (ValidationException $e) { // Tangkap ValidationException
            $errors = $e->validator->errors()->all();
            Log::error('Validasi gagal: ' . implode(', ', $errors));
            return redirect()->back()
                ->withErrors($e->validator)
                ->with([
                    'message' => 'Validasi gagal: ' . implode(', ', $errors),
                    'alert-type' => 'error'
                ])
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error dalam StoreData: ' . $e->getMessage());
            return redirect()->back()
                ->with([
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                    'alert-type' => 'error'
                ])
                ->withInput();
        }
    }

    private function cleanNumericValue($value): float
    {
        if (is_null($value) || $value === '') {
            return 0.0;
        }

        $value = (string) $value;
        $cleaned = preg_replace('/[^0-9]/', '', $value); // Hapus semua karakter non-digit

        if (empty($cleaned)) {
            return 0.0;
        }

        return (float)$cleaned;
    }


    // Metode lain tetap sama seperti sebelumnya...
    public function CustomSave(Request $request)
    {
        Log::info('Starting to save custom package!');

        // Validasi input
        $validatedData = $request->validate([
            'saveCustAgen' => 'exists:users,id',
            'saveCustName' => 'required|string',
            'saveCustType' => 'required|string',
            'saveStatus' => 'required|string',
            'regency' => 'exists:regencies,id',
        ]);

        // Ambil data dari model Custom dengan ID 1
        $existingCustomData = Custom::first(); // Gunakan first() karena kita menyimpan hasil perhitungan terakhir di baris pertama

        if (!$existingCustomData) {
            return redirect()->route('calculate.custom.package')->with([
                'message' => 'Data Custom tidak ditemukan! Harap lakukan perhitungan terlebih dahulu.',
                'alert-type' => 'error',
            ]);
        }

        // Decode JSON data dari model Custom
        $decodedData = json_decode($existingCustomData->custompackage, true);

        if (!$decodedData) {
            return redirect()->route('calculate.custom.package')->with([
                'message' => 'Gagal membaca data JSON pada Custom!',
                'alert-type' => 'error',
            ]);
        }

        // Tambahkan data baru dari form
        $newData = [
            'transportCost' => $decodedData['transportCost'] ?? 0,
            'parkingCost' => $decodedData['parkingCost'] ?? 0,
            'ticketCost' => $decodedData['ticketCost'] ?? 0,
            'hotelCost' => $decodedData['hotelCost'] ?? 0,
            'extraBedCost' => $decodedData['extraBedCost'] ?? 0, // Pastikan ini juga disimpan
            'otherFee' => $decodedData['otherFee'] ?? "0",
            'reservedFee' => $decodedData['reservedFee'] ?? "0",
            'totalMealCost' => $decodedData['totalMealCost'] ?? 0,
            'facilityCost' => $decodedData['facilityCost'] ?? 0,
            'totalCost' => $decodedData['totalCost'] ?? 0,
            'DurationPackage' => $decodedData['DurationPackage'] ?? "0",
            'night' => $decodedData['night'] ?? "0",
            'downPayment' => $decodedData['downPayment'] ?? 0,
            'remainingCosts' => $decodedData['remainingCosts'] ?? 0,
            'costPerPerson' => $decodedData['costPerPerson'] ?? 0,
            'childCost' => $decodedData['childCost'] ?? 0,
            'participants' => $decodedData['participants'] ?? "0",
            'additionalCostWna' => $decodedData['additionalCostWna'] ?? 0,
            'destinationNames' => $decodedData['destinationNames'] ?? [],
            'facilityNames' => $decodedData['facilityNames'] ?? [],
            'hotelNames' => $decodedData['hotelNames'] ?? [], // Simpan nama hotel juga
            'agen_id' => $validatedData['saveCustAgen'],
            'regency_id' => $validatedData['regency'],
            'status' => $validatedData['saveStatus'],
            'package_name' => $validatedData['saveCustName'],
            'package_type' => $validatedData['saveCustType'],
        ];

        // Simpan data baru dengan ID baru (ini akan membuat entri baru di tabel Custom)
        Custom::create([
            'custompackage' => json_encode($newData),
        ]);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Data Custom berhasil disimpan!',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.custom.package')->with($notification);
    }

    public function IndexCustom()
    {
        // Ambil data agen
        $allagens = User::where('role', 'agen')->get();

        // Ambil semua data Custom kecuali ID 1 (yang digunakan untuk perhitungan sementara)
        $allCustPackages = Custom::where('id', '!=', 1)->get();

        // Siapkan data Custom dengan username agen dan ID
        $customData = $allCustPackages->map(function ($custom) use ($allagens) {
            $customPackage = json_decode($custom->custompackage, true);
            $agen = $allagens->firstWhere('id', $customPackage['agen_id'] ?? null);
            $customPackage['agen_name'] = $agen ? $agen->username : 'Unknown Agen';
            $customPackage['id'] = $custom->id; // Tambahkan ID Custom
            return $customPackage;
        });

        return view('admin.custom.index', compact('customData', 'allagens'));
    }

    public function getCustomPackage($id)
    {
        $custom = Custom::find($id);

        if (!$custom) {
            return response()->json(['success' => false, 'message' => 'Data not found']);
        }

        $prices = json_decode($custom->custompackage, true);

        return response()->json([
            'success' => true,
            'prices' => $prices,
        ]);
    }


    public function DeleteCustomPackage($id)
    {
        try {
            // Cari paket berdasarkan ID
            $package = Custom::find($id);

            if (!$package) {
                return redirect()->route('all.custom.package')->with('error', 'Package not found!');
            }

            // Hapus paket
            $package->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data has been successfully deleted',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete data',
            ], 500);
        }
    }
}
