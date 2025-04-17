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
use App\Models\PackageTwoDay;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class GenerateTwodayPackageController extends Controller
{

    public function AllTwoDayPackage(Request $request)
    {

        $packages = PackageTwoDay::all();
        $destinations = Destination::all(); // Untuk form generate
        $active = PackageTwoDay::where('status', 1)->count();
        $inactive = PackageTwoDay::where('status', 0)->count();
        $agens = User::agen()->get();
        return view('admin.package.twoday.all_packages', compact('packages', 'destinations', 'active', 'inactive', 'agens'));
    }

    public function GenerateTwodayPackage(Request $request)
    {

        $destinations = Destination::all();
        $agens = User::agen()->get();
        $regencies = Regency::all();
        $facilities = Facility::all();

        return view('admin.package.twoday.generate_package_twoday', compact('destinations', 'agens', 'regencies', 'facilities'));
    }

    public function generateCodeTwoday(Request $request)
    {
        try {
            Log::info('generateCodeTwoday method initiated.');

            // Validasi input menggunakan FormRequest
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

            // Ambil data dari request
            $namePackage = $validatedData['NamePackage'];
            $regencyId = $validatedData['cityOrDistrict_id'];
            $agenId = $validatedData['NameAgen'];
            $statusPackage = $validatedData['statusPackage'];
            $information = $request->input('information', '');
            $destinationIds = $validatedData['destinations'];
            $facilityIds = $validatedData['facilities'];

            // Ambil data terkait
            $vehicles = Vehicle::all();
            $hotels = Hotel::active()->byRegency($regencyId)->get();
            $meals = Meal::forDuration(2)->byRegency($regencyId)->first();
            $crewData = Crew::all();
            $serviceFee = ServiceFee::forDuration(2)->value('mark') ?? 0.14;
            $feeAgen = AgenFee::defaultFee();
            $reserveFees = ReserveFee::forDuration(2)->get();
            $selectedDestinations = Destination::byIdsAndRegency($destinationIds, $regencyId);
            $selectedFacilities = Facility::byIdsAndRegency($facilityIds, $regencyId);

            Log::info('Hotels and facilities loaded.', [
                'hotels_count' => $hotels->count(),
                'facilities_count' => $selectedFacilities->count(),
                'destinations_count' => $selectedDestinations->count(),
            ]);

            // Simpan paket wisata ke database
            $package = PackageTwoDay::create([
                'name_package' => $namePackage,
                'regency_id' => $regencyId,
                'agen_id' => $agenId,
                'status' => $statusPackage,
                'information' => $information,
            ]);

            Log::info('Package saved.', ['package_id' => $package->id]);

            // Simpan destinasi dan fasilitas
            $package->destinations()->sync($destinationIds);
            $package->facilities()->sync($facilityIds);

            // Hitung harga
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

            // Simpan harga
            $package->prices()->create([
                'price_data' => json_encode($prices),
            ]);

            Log::info('Prices saved.', ['package_id' => $package->id]);

            return redirect()->route('all.twoday.packages')->with([
                'message' => 'Package generated successfully!',
                'alert-type' => 'success',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error.', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            Log::error('Unexpected error.', ['message' => $e->getMessage()]);
            return redirect()->back()->with([
                'message' => 'An error occurred while generating package.',
                'alert-type' => 'error',
            ])->withInput();
        }
    }

    public function EditGenerateTwodayPackage($id)
    {

        $package = PackageTwoDay::with('destinations')->find($id);

        if (!$package) {
            return redirect()->route('all.packages')->with('error', 'Package not found!');
        }

        $destinations = Destination::all();
        $selectedDestinations = $package->destinations->pluck('id')->toArray();
        $facilities = Facility::all();
        $selectedFacilities = $package->facilities->pluck('id')->toArray();
        $agens = User::agen()->get();
        $regencies = Regency::all();

        return view('admin.package.twoday.edit_package', compact('destinations', 'agens', 'regencies', 'package', 'selectedDestinations', 'facilities', 'selectedFacilities'));
    }

    public function UpdateGenerateCodeTwoday(Request $request, $id)
    {
        try {
            Log::info('generateCodeTwoday method initiated.');

            // Validasi input menggunakan FormRequest
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

            // Ambil data dari request
            $namePackage = $validatedData['NamePackage'];
            $regencyId = $validatedData['cityOrDistrict_id'];
            $agenId = $validatedData['NameAgen'];
            $statusPackage = $validatedData['statusPackage'];
            $information = $request->input('information', '');
            $destinationIds = $validatedData['destinations'];
            $facilityIds = $validatedData['facilities'];

            // Ambil data terkait
            $vehicles = Vehicle::all();
            $hotels = Hotel::active()->byRegency($regencyId)->get();
            $meals = Meal::forDuration(2)->first();
            $crewData = Crew::all();
            $serviceFee = ServiceFee::forDuration(2)->value('mark') ?? 0.14;
            $feeAgen = AgenFee::defaultFee();
            $reserveFees = ReserveFee::forDuration(2)->get();
            $selectedDestinations = Destination::byIdsAndRegency($destinationIds, $regencyId);
            $selectedFacilities = Facility::byIdsAndRegency($facilityIds, $regencyId);

            Log::info('Hotels and facilities loaded.', [
                'hotels_count' => $hotels->count(),
                'facilities_count' => $selectedFacilities->count(),
                'destinations_count' => $selectedDestinations->count(),
            ]);

            // Cari data package berdasarkan ID
            $package = PackageTwoDay::find($id);

            if (!$package) {
                return redirect()->route('all.twoday.packages')->with('error', 'Package not found!');
            }

            // Simpan paket wisata ke database
            $package->update([
                'name_package' => $namePackage,
                'regency_id' => $regencyId,
                'agen_id' => $agenId,
                'status' => $statusPackage,
                'information' => $information,
            ]);

            Log::info('Package saved to database.', ['package' => $package]);

            // Simpan destinasi dan fasilitas
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

            return redirect()->route('all.twoday.packages')->with($notification);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in UpdateGenerateCodeTwoday.', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error in UpdateGenerateCodeTwoday.', ['message' => $e->getMessage()]);
            return redirect()->back()->with([
                'message' => 'An error occurred while updating the package.',
                'alert-type' => 'error'
            ])->withInput();
        }
    }


    private function calculatePrices($vehicles, $meals, $crewData, $serviceFee, $feeAgen, $reserveFees, $selectedDestinations, $selectedFacilities, $hotels, $regencyId, $days = 2)
    {
        $pricesWithMeal = [["Price Type" => "Include Meal"]];
        $pricesWithoutMeal = [["Price Type" => "Exclude Meal"]];

        for ($participants = 1; $participants <= 55; $participants++) {
            $vehicle = $vehicles->firstWhere(fn($v) => $participants >= $v->capacity_min && $participants <= $v->capacity_max);
            if (!$vehicle) {
                continue;
            }

            $transportCost = $vehicle->price * $days;
            $crew = $crewData->firstWhere(fn($c) => $participants >= $c->min_participants && $participants <= $c->max_participants);

            [$totalCostWNI, $totalCostWNA, $parkingCost] = $this->calculateDestinationCosts($selectedDestinations, $participants, $vehicle);
            $totalFacilityCost = $this->calculateFacilityCosts($selectedFacilities, $participants);
            $reserveFee = $reserveFees->firstWhere(fn($r) => $participants >= $r->min_user && $participants <= $r->max_user);
            $reserveFeeCost = $reserveFee ? $reserveFee->price * $participants * $days : 0;

            foreach ([true, false] as $includeMeal) {
                $mealCost = $meals ? $meals->price * $meals->num_meals * ($includeMeal ? ($participants + $crew->num_crew) : $crew->num_crew) : 0;
                $priceRow = [
                    'vehicle' => $vehicle->name,
                    'user' => $participants,
                    'wnaCost' => round(($totalCostWNA - $totalCostWNI) / $participants, 2),
                    'mealCostPerPerson' => round($mealCost / $participants, 2)
                ];

                if ($hotels->isNotEmpty()) {
                    foreach ($hotels as $hotel) {
                        $hotelCost = $this->calculateHotelCost($hotel, $participants);
                        $totalCost = $totalCostWNI + $transportCost + ($feeAgen * $participants * $days) + $hotelCost + $mealCost + $reserveFeeCost + $parkingCost + $totalFacilityCost;
                        $pricePerPerson = $totalCost / $participants;
                        $finalPrice = $pricePerPerson + ($pricePerPerson * $serviceFee);
                        $priceRow[$hotel->type] = round($finalPrice, 2);
                    }
                } else {
                    Log::warning('No hotels found for regency', ['regencyId' => $regencyId]);
                }

                if ($includeMeal) {
                    $pricesWithMeal[] = $priceRow;
                } else {
                    $pricesWithoutMeal[] = $priceRow;
                }
            }
        }

        return [
            ["Price Type" => "Include Meal", "data" => $pricesWithMeal],
            ["Price Type" => "Exclude Meal", "data" => $pricesWithoutMeal]
        ];
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

        // Cek fasilitas 'flat' di seluruh $facilities
        $hasFlat = false;
        foreach ($facilities as $facility) {
            if ($facility->type === 'flat') {
                $hasFlat = true;
                break; // Keluar dari loop begitu 'flat' ditemukan
            }
        }


        foreach ($facilities as $facility) {
            $groupCount = ceil($participants / ($facility->max_user ?? $participants)); // Hitung grup berdasarkan max_user

            switch ($facility->type) {
                case 'flat':
                    // Hitung biaya flat
                    $flatCost += $groupCount * $facility->price;

                case 'shuttle':
                    // Hanya hitung jika peserta memenuhi syarat 18-55
                    if ($participants >= 18 && $participants <= 55) {
                        // Jika ada fasilitas 'flat', hitung biaya shuttle berdasarkan durasi
                        if ($hasFlat) {
                            $ShuttleCost += $groupCount * $facility->price; // x1
                        } else {
                            // Jika tidak ada 'flat', hitung shuttle dengan logika default
                            $ShuttleCost += $groupCount * $facility->price * 2;
                        }
                    }
                    break;

                case 'per_day':
                    // Hitung biaya per hari
                    $facPerdayCost += $facility->price * 2;
                    break;

                case 'doc':
                    // Hitung biaya doc jika peserta dalam rentang 20-55
                    if ($participants >= 20 && $participants <= 55) {
                        $facDocCost += $facility->price * 2;
                    }
                    break;

                case 'tl':
                    // Hitung biaya guide jika peserta dalam rentang 20-55
                    if ($participants >= 20 && $participants <= 55) {
                        $guideCost += $groupCount * $facility->price * 2;
                    }
                    break;

                case 'per_person':
                    // Hitung biaya per orang
                    $facPerpersonCost += $facility->price * $participants * 2;
                    break;

                case 'event':
                    // Hitung biaya event
                    $facEventCost += $facility->price;
                    break;

                case 'info':
                    // Hitung biaya info
                    $facInfoCost += $facility->price * 2;
                    break;
            }

            $totalFacilityCost = $flatCost + $ShuttleCost + $facPerdayCost + $facDocCost + $guideCost + $facPerpersonCost + $facEventCost + $facInfoCost;
        }

        return $totalFacilityCost;
    }

    private function calculateHotelCost($hotel, $participants, $nights = 1)
    {
        $capacity = $hotel->capacity ?? 2;
        $extraBedPrice = $hotel->extrabed_price ?? 0;

        if ($capacity <= 0) {
            Log::warning('Hotel capacity is zero or null, setting default to 2', ['hotel' => $hotel->name]);
            $capacity = 2;
        }

        if (in_array($hotel->type, ['Villa', 'Homestay', 'Cottage', 'Cabin'])) {
            // Perbaikan: Tangani kasus kapasitas > peserta
            if ($capacity > $participants) {
                $numUnits = 1;  // Hanya butuh 1 unit
                $remainingParticipants = 0; // Tidak ada sisa peserta
                $totalCost = ($hotel->price ?? 0); // Biaya 1 unit
            } else {
                $numUnits = intdiv($participants, $capacity);
                $remainingParticipants = $participants % $capacity;
                $totalCost = $numUnits * ($hotel->price ?? 0);

                if ($remainingParticipants > 0) {
                    if ($remainingParticipants <= 2) {
                        $totalCost += ($remainingParticipants * $extraBedPrice);
                    } else {
                        $totalCost += ($hotel->price ?? 0);
                        $remainingParticipants -= $capacity;
                        if ($remainingParticipants > 0) {
                            $totalCost += ($remainingParticipants <= 2 ? $remainingParticipants * $extraBedPrice : 2 * $extraBedPrice);
                        }
                    }
                }
            }

            return $totalCost * $nights;
        }

        $numRooms = intdiv($participants, 2);
        $extraBedCost = 0;

        if ($participants % 2 !== 0) {
            $numRooms += 1;
            $extraBedCost = $extraBedPrice;
        }

        return (($hotel->price ?? 0) * $numRooms + $extraBedCost) * $nights;
    }

    public function AllTwodayPackagesAgen($id)
    {
        // Ambil data agen berdasarkan ID
        $agen = User::where('role', 'agen')->where('id', $id)->first();

        if (!$agen) {
            return redirect()->back()->with('error', 'Agen not found!');
        }

        // Ambil semua paket yang dimiliki oleh agen ini
        $packages = PackageTwoDay::where('agen_id', $id)->with(['destinations', 'prices'])->paginate(5);

        // Ambil data destinasi dan kabupaten untuk dropdown (opsional)
        $destinations = Destination::all();
        $facilities = Facility::all();
        $regencies = Regency::all();

        return view('admin.package.twoday.data_package', compact('destinations', 'regencies', 'packages', 'agen', 'facilities'));
    }

    public function PackageTwodayShow($id)
    {
        // Ambil data paket berdasarkan ID, termasuk relasi
        $package = PackageTwoDay::with(['destinations', 'prices', 'regency'])->findOrFail($id);

        // Ambil data destinasi dan kabupaten untuk dropdown (opsional)
        $destinations = Destination::all();
        $facilities = Facility::all();
        $regencies = Regency::all();

        return view('admin.package.twoday.show_package', compact('destinations', 'regencies', 'package', 'facilities'));
    }


    public function DeleteTwodayPackage($id)
    {
        try {
            // Cari paket berdasarkan ID
            $package = PackageTwoDay::find($id);

            if (!$package) {
                return redirect()->route('all.twoday.packages')->with('error', 'Package not found!');
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
