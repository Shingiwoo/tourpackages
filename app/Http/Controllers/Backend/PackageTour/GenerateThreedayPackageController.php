<?php

namespace App\Http\Controllers\Backend\PackageTour;

use App\Models\Crew;
use App\Models\Meal;
use App\Models\User;
use App\Models\Hotel;
use App\Models\AgenFee;
use App\Models\Regency;
use App\Models\Vehicle;
use App\Models\Facility;
use App\Models\ReserveFee;
use App\Models\ServiceFee;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\PackageThreeDay;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class GenerateThreedayPackageController extends Controller
{
    public function AllThreeDayPackage(Request $request){

        $packages = PackageThreeDay::all();
        $destinations = Destination::all(); // Untuk form generate
        $active = PackageThreeDay::where('status', 1)->count();
        $inactive = PackageThreeDay::where('status', 0)->count();
        $agens = User::agen()->get();
        return view('admin.package.threeday.all_packages', compact('packages', 'destinations', 'active', 'inactive', 'agens'));
    }

    public function GenerateThreeDayPackage(Request $request){

        $destinations = Destination::all();
        $agens = User::agen()->get();
        $regencies = Regency::all();
        $facilities = Facility::all();

        return view('admin.package.threeday.generate_package_threeday', compact('destinations', 'agens', 'regencies', 'facilities'));
    }

    public function generateCodeThreeDay(Request $request){
        try {
            Log::info('generateCodeThreeDay method initiated.');

            // Validasi input
            $validatedData = $request->validate([
                'NamePackage' => 'required|string|max:255',
                'cityOrDistrict_id' => 'required|exists:regencies,id',
                'statusPackage' => 'required|boolean',
                'NameAgen' => 'required|exists:users,id',
                'facilities' => 'required|array',
                'facilities.*' => 'exists:facilities,id',
                'destinations' => 'required|array',
                'destinations.*' => 'exists:destinations,id',
            ]);
            Log::info('Validation passed.', ['validated_data' => $validatedData]);

            // Ambil data dari request
            $namePackage = $request->input('NamePackage');
            $regencyId = $request->input('cityOrDistrict_id');
            $agenId = $request->input('NameAgen');
            $statusPackage = $request->input('statusPackage');
            $information = $request->input('information', '');
            $destinationIds = $request->input('destinations');
            $facilityIds = $validatedData['facilities'];

            // Ambil data terkait
            $vehicles = Vehicle::all();
            $hotels = Hotel::active()->byRegency($regencyId)->get(); // Gunakan scope
            $meals = Meal::forDuration(3)->first(); // Gunakan scope
            $crewData = Crew::all();
            $serviceFee = ServiceFee::forDuration(3)->value('mark') ?? 0.14; // Ambil nilai langsung
            $feeAgen = AgenFee::defaultFee();
            $reserveFees = ReserveFee::forDuration(3)->get();
            $selectedDestinations = Destination::byIdsAndRegency($destinationIds, $regencyId);
            $selectedFacilities = Facility::byIdsAndRegency($facilityIds, $regencyId);

            Log::info('Hotels and facilities loaded.', [
                'hotels_count' => $hotels->count(),
                'facilities_count' => $selectedFacilities->count(),
                'destinations_count' => $selectedDestinations->count(),
            ]);

            // Simpan paket wisata ke database
            $package = PackageThreeDay::create([
                'name_package' => $namePackage,
                'regency_id' => $regencyId,
                'agen_id' => $agenId,
                'status' => $statusPackage,
                'information' => $information,
            ]);
            Log::info('Package saved to database.', ['package' => $package]);

            // Simpan destinasi untuk paket
            $package->destinations()->sync($destinationIds);
            $package->facilities()->sync($facilityIds);

            // Hitung harga per jenis hotel dan jumlah peserta
            $prices = $this->calculatePrices(
                $vehicles,
                $meals,
                $crewData,
                $serviceFee,
                $feeAgen,
                $reserveFees,
                $selectedDestinations,
                $selectedFacilities,
                $hotels,
                $regencyId
            );

            // Simpan harga ke database (dalam format JSON)
            $package->prices()->create([
                'price_data' => json_encode($prices),
            ]);
            // Log::info('Prices saved to database.', ['prices' => $prices]);
            Log::info('Prices saved to database successfully!');

            return redirect()->route('all.threeday.packages')->with([
                'message' => 'Package generated successfully!',
                'alert-type' => 'success',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in generateCodeThreeDay.', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error in generateCodeThreeDay.', ['message' => $e->getMessage()]);
            return redirect()->back()->with([
                'message' => 'An error occurred while generating package.',
                'alert-type' => 'error'
            ])->withInput();
        }
    }


    public function EditGenerateThreeDayPackage($id){

        $package = PackageThreeDay::with('destinations')->find($id);

        if (!$package) {
            return redirect()->route('all.packages')->with('error', 'Package not found!');
        }

        $destinations = Destination::all();
        $selectedDestinations = $package->destinations->pluck('id')->toArray();
        $facilities = Facility::all();
        $selectedFacilities = $package->facilities->pluck('id')->toArray();
        $agens = User::agen()->get();
        $regencies = Regency::all();

        return view('admin.package.threeday.edit_package', compact('destinations', 'agens', 'regencies', 'package', 'selectedDestinations', 'facilities', 'selectedFacilities'));
    }

    public function UpdateGenerateCodeThreeDay(Request $request, $id){
        try {

            Log::info('UpdateGenerateCodeThreeDay method initiated.');

            // Validasi input
            $validatedData = $request->validate([
                'NamePackage' => 'required|string|max:255',
                'cityOrDistrict_id' => 'required|exists:regencies,id',
                'statusPackage' => 'required|boolean',
                'NameAgen' => 'required|exists:users,id',
                'facilities' => 'required|array',
                'facilities.*' => 'exists:facilities,id',
                'destinations' => 'required|array',
                'destinations.*' => 'exists:destinations,id',
            ]);
            Log::info('Validation passed.', ['validated_data' => $validatedData]);

            // Ambil data dari request
            $namePackage = $request->input('NamePackage');
            $regencyId = $request->input('cityOrDistrict_id');
            $agenId = $request->input('NameAgen');
            $statusPackage = $request->input('statusPackage');
            $information = $request->input('information', '');
            $destinationIds = $request->input('destinations');
            $facilityIds = $validatedData['facilities'];

            // Ambil data terkait
            $vehicles = Vehicle::all();
            $hotels = Hotel::active()->byRegency($regencyId)->get();
            $meals = Meal::forDuration(3)->first();
            $crewData = Crew::all();
            $serviceFee = ServiceFee::forDuration(3)->value('mark') ?? 0.14;
            $feeAgen = AgenFee::defaultFee();
            $reserveFees = ReserveFee::forDuration(3)->get();
            $selectedDestinations = Destination::byIdsAndRegency($destinationIds, $regencyId);
            $selectedFacilities = Facility::byIdsAndRegency($facilityIds, $regencyId);

            Log::info('Hotels and facilities loaded.', [
                'hotels_count' => $hotels->count(),
                'facilities_count' => $selectedFacilities->count(),
                'destinations_count' => $selectedDestinations->count(),
            ]);

            // Cari data package berdasarkan ID
            $package = PackageThreeDay::find($id);

            if (!$package) {
                return redirect()->route('all.threeday.packages')->with('error', 'Package not found!');
            }

            // Update paket wisata di database
            $package->update([
                'name_package' => $namePackage,
                'regency_id' => $regencyId,
                'agen_id' => $agenId,
                'status' => $statusPackage,
                'information' => $information,
            ]);

            Log::info('Package update to database.', ['package' => $package]);

            // Simpan destinasi untuk paket
            $package->destinations()->sync($destinationIds);
            $package->facilities()->sync($facilityIds);

            // Hitung harga per jenis hotel dan jumlah peserta
            $prices = $this->calculatePrices(
                $vehicles,
                $meals,
                $crewData,
                $serviceFee,
                $feeAgen,
                $reserveFees,
                $selectedDestinations,
                $selectedFacilities,
                $hotels,
                $regencyId
            );

            // Update harga di database (dalam format JSON) jika sudah ada, atau buat baru jika belum ada
            $priceRecord = $package->prices()->first();
            if ($priceRecord) {
                $priceRecord->update([
                    'price_data' => json_encode($prices),
                ]);
                Log::info('Prices updated in database successfully!');
            } else {
                $package->prices()->create([
                    'price_data' => json_encode($prices),
                ]);
                Log::info('Prices created in database successfully!');
            }

            // Kirim notifikasi berhasil
            $notification = [
                'message' => 'Package updated successfully!',
                'alert-type' => 'success',
            ];
            Log::info('Notification prepared.', ['notification' => $notification]);

            return redirect()->route('all.threeday.packages')->with($notification);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in UpdateGenerateCodeThreeDay.', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error in UpdateGenerateCodeThreeDay.', ['message' => $e->getMessage()]);
            return redirect()->back()->with([
                'message' => 'An error occurred while update generating package.',
                'alert-type' => 'error'
            ])->withInput();
        }
    }

    private function calculatePrices($vehicles, $meals, $crewData, $serviceFee, $feeAgen, $reserveFees, $selectedDestinations, $selectedFacilities, $hotels, $regencyId){
        $prices = [];

        for ($participants = 1; $participants <= 55; $participants++) {
            // Pilih kendaraan berdasarkan jumlah peserta
            $vehicle = $vehicles->firstWhere(fn($v) => $participants >= $v->capacity_min && $participants <= $v->capacity_max);
            if (!$vehicle) {
                continue;
            }

            $transportCost = $vehicle->price * 3;
            $crew = $crewData->firstWhere(fn($c) => $participants >= $c->min_participants && $participants <= $c->max_participants);

            // Biaya destinasi dan parkir
            [$totalCostWNI, $totalCostWNA, $parkingCost] = $this->calculateDestinationCosts($selectedDestinations, $participants, $vehicle);

            // Biaya fasilitas
            $totalFacilityCost = $this->calculateFacilityCosts($selectedFacilities, $participants);

            // Biaya makanan
            $mealCost = $meals ? $meals->price * $meals->num_meals * ($participants + $crew->num_crew) : 0;

            // Biaya reservasi
            $reserveFee = $reserveFees->firstWhere(fn($r) => $participants >= $r->min_user && $participants <= $r->max_user);
            $reserveFeeCost = $reserveFee ? $reserveFee->price * $participants * 3 : 0;

            // Hitung harga untuk setiap jenis akomodasi
            $priceRow = [
                'vehicle' => $vehicle->name,
                'user' => $participants,
                'wnaCost' => round(($totalCostWNA - $totalCostWNI) / $participants, 2),
                'mealCostPerPerson' => round($mealCost / $participants, 2)
            ];

            if ($hotels->isNotEmpty()) {
                foreach ($hotels as $hotel) {
                    $hotelCost = $this->calculateHotelCost($hotel, $participants);
                    $totalCost = $totalCostWNI + $transportCost + ($feeAgen * $participants * 3) + $hotelCost + $mealCost + $reserveFeeCost + $parkingCost + $totalFacilityCost;

                    $pricePerPerson = $totalCost / $participants;
                    $finalPrice = $pricePerPerson + ($pricePerPerson * $serviceFee);

                    $priceRow[$hotel->type] = round($finalPrice, 2);
                }
            } else {
                Log::warning('No hotels found for regency', ['regencyId' => $regencyId]);
            }

            $prices[] = $priceRow;
        }

        return $prices;
    }

    private function calculateDestinationCosts($destinations, $participants, $vehicle)
    {
        $totalCostWNI = 0;
        $totalCostWNA = 0;
        $parkingCost = 0;

        foreach ($destinations as $destination) {
            if ($destination->price_type === 'per_person') {
                $totalCostWNI += $destination->price_wni * $participants;
                $totalCostWNA += $destination->price_wna * $participants;
            } elseif ($destination->price_type === 'flat') {
                $groupCount = ceil($participants / $destination->max_participants);
                $totalCostWNI += $groupCount * $destination->price_wni;
                $totalCostWNA += $groupCount * $destination->price_wna;
            }

            $parkingCosts = [
                'City Car' => $destination->parking_city_car,
                'Mini Bus' => $destination->parking_mini_bus,
                'Bus' => $destination->parking_bus,
            ];

            $parkingCost += $parkingCosts[$vehicle->type] ?? 0;
        }

        return [$totalCostWNI, $totalCostWNA, $parkingCost];
    }

    private function calculateFacilityCosts($facilities, $participants)
    {
        // Inisialisasi biaya fasilitas
        $totalFacilityCost = 0;
        $ShuttleCost = 0;
        $flatCost = 0;
        $facPerdayCost = 0;
        $facPerpersonCost = 0;
        $facInfoCost = 0;
        $facEventCost = 0;
        $facDocCost = 0;
        $guideCost = 0;

        foreach ($facilities as $facility) {
            $groupCount = ceil($participants / ($facility->max_user ?? $participants)); // Hitung grup berdasarkan max_user

            switch ($facility->type) {
                case 'flat':
                    // Hitung biaya flat
                    $flatCost += $groupCount * $facility->price;

                    // Jika memenuhi syarat shuttle, hitung biaya shuttle
                    if ($participants >= 18 && $participants <= 55 && $facility->type === 'shuttle') {
                        $ShuttleCost += $groupCount * $facility->price * 2;
                    }
                    break;

                case 'shuttle':
                    // Hitung biaya shuttle dengan syarat peserta
                    if ($participants >= 18 && $participants <= 55) {
                        $ShuttleCost += $groupCount * $facility->price * 3;
                    }
                    break;

                case 'per_day':
                    // Hitung biaya per hari
                    $facPerdayCost += $facility->price * 3;
                    break;

                case 'doc':
                    // Hitung biaya doc jika peserta dalam rentang 20-55
                    if ($participants >= 20 && $participants <= 55) {
                        $facDocCost += $facility->price * 3;
                    }
                    break;

                case 'tl':
                    // Hitung biaya guide jika peserta dalam rentang 20-55
                    if ($participants >= 20 && $participants <= 55) {
                        $guideCost += $groupCount * $facility->price * 3;
                    }
                    break;

                case 'per_person':
                    // Hitung biaya per orang
                    $facPerpersonCost += $facility->price * $participants * 3;
                    break;

                case 'event':
                    // Hitung biaya event
                    $facEventCost += $facility->price * 3;
                    break;

                case 'info':
                    // Hitung biaya info
                    $facInfoCost += $facility->price * 3;
                    break;
            }

            $totalFacilityCost = $flatCost + $ShuttleCost + $facPerdayCost + $facDocCost + $guideCost + $facPerpersonCost + $facEventCost + $facInfoCost;
        }

        return $totalFacilityCost;
    }

    private function calculateHotelCost($hotel, $participants)
    {
        // Jika jenis hotel adalah Villa, Homestay, Cottage, atau Cabin
        if (in_array($hotel->type, ['Villa', 'Homestay', 'Cottage', 'Cabin'])) {
            $capacity = $hotel->capacity; // Kapasitas per unit
            $extraBedPrice = $hotel->extrabed_price; // Harga extra bed

            // Hitung unit penuh yang diperlukan
            $numUnits = intdiv($participants, $capacity);

            // Hitung peserta yang tersisa setelah unit penuh
            $remainingParticipants = $participants % $capacity;

            // Biaya untuk unit penuh
            $totalCost = $numUnits * $hotel->price;

            // Jika ada peserta tersisa, tambahkan biaya extra bed
            if ($remainingParticipants > 0) {
                // Tambahkan biaya 1 unit penuh untuk sisa peserta
                $totalCost += $hotel->price * 2;

                // Tambahkan biaya extra bed untuk peserta tersisa
                if ($remainingParticipants <= 2) {
                    $totalCost += ($remainingParticipants * $extraBedPrice * 2);
                }
            }

            return $totalCost;
        }

        // Jika bukan jenis hotel yang memerlukan perhitungan kapasitas
        $numRooms = intdiv($participants, 2);
        $extraBedCost = 0;

        if ($participants % 2 !== 0) {
            $numRooms += 1;
            $extraBedCost = $hotel->extrabed_price * 2;
        }

        return ($hotel->price * $numRooms * 2) + $extraBedCost;
    }

    public function AllThreeDayPackagesAgen($id)
    {
        // Ambil data agen berdasarkan ID
        $agen = User::where('role', 'agen')->where('id', $id)->first();

        if (!$agen) {
            return redirect()->back()->with('error', 'Agen not found!');
        }

        // Ambil semua paket yang dimiliki oleh agen ini
        $packages = PackageThreeDay::where('agen_id', $id)->with(['destinations', 'prices'])->paginate(5);

        // Ambil data destinasi dan kabupaten untuk dropdown (opsional)
        $destinations = Destination::all();
        $facilities = Facility::all();
        $regencies = Regency::all();

        return view('admin.package.threeday.data_package', compact('destinations', 'regencies', 'packages', 'agen', 'facilities'));
    }

    public function PackageThreeDayShow($id)
    {
        // Ambil data paket berdasarkan ID, termasuk relasi
        $package = PackageThreeDay::with(['destinations', 'prices', 'regency'])->findOrFail($id);

        // Ambil data destinasi dan kabupaten untuk dropdown (opsional)
        $destinations = Destination::all();
        $facilities = Facility::all();
        $regencies = Regency::all();

        return view('admin.package.threeday.show_package', compact('destinations', 'regencies', 'package', 'facilities'));
    }

    public function DeleteThreeDayPackage($id)
    {
        try {
            // Cari paket berdasarkan ID
            $package = PackageThreeDay::find($id);

            if (!$package) {
                return redirect()->route('all.threeday.packages')->with('error', 'Package not found!');
            }

            // Hapus relasi destinasi (pivot table)
            $package->destinations()->detach();
            $package->facilities()->detach();

            // Hapus relasi harga
            $package->prices()->delete();

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
