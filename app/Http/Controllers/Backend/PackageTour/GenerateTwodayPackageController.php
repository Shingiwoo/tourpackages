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

    public function AllTwoDayPackage(Request $request){

        $packages = PackageTwoDay::all();
        $destinations = Destination::all(); // Untuk form generate
        $active = PackageTwoDay::where('status', 1)->count();
        $inactive = PackageTwoDay::where('status', 0)->count();
        $agens = User::agen()->get();
        return view('admin.package.twoday.all_packages', compact('packages', 'destinations', 'active', 'inactive', 'agens'));
    }

    public function GenerateTwodayPackage(Request $request){

        $destinations = Destination::all();
        $agens = User::agen()->get();
        $regencies = Regency::all();
        $facilities = Facility::all();

        return view('admin.package.twoday.generate_package_twoday', compact('destinations', 'agens', 'regencies', 'facilities'));
    }

    public function generateCodeTwoday(Request $request){
        try {
            Log::info('generateCodeTwoday method initiated.');

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
            $facilityIds = $request->input('facilities');

            // Log::info('Input data processed.', [
            //     'namePackage' => $namePackage,
            //     'regencyId' => $regencyId,
            //     'agenId' => $agenId,
            //     'statusPackage' => $statusPackage,
            //     'destinationIds' => $destinationIds,
            // ]);

            // Ambil data terkait
            $vehicles = Vehicle::all();
            $hotels = Hotel::where('status', 1)->where('regency_id', $regencyId)->get();
            $meals = Meal::where('duration', '2')->first();
            $crewData = Crew::all();
            $serviceFee = ServiceFee::where('duration', '2')->first()->mark ?? 0.14;
            $feeAgen = AgenFee::find(1)->price ?? 50000;
            $reserveFees = ReserveFee::where('duration', '2')->get();
            $selectedDestinations = Destination::whereIn('id', $destinationIds)
                ->where('regency_id', $regencyId)
                ->get();
            $selectedFacilities = Facility::whereIn('id', $facilityIds)
                ->where('regency_id', $regencyId)
                ->get();

            // Log::info('Hotels and facilities loaded', [
            //     'hotels' => $hotels,
            //     'facilities' => $selectedFacilities,
            //     'destinations' => $selectedDestinations,
            // ]);

            // Log::info('Supporting data fetched.', [
            //     'vehicles' => $vehicles,
            //     'meals' => $meals,
            //     'crewData' => $crewData,
            //     'serviceFee' => $serviceFee,
            //     'feeAgen' => $feeAgen,
            //     'reserveFees' => $reserveFees,
            //     'selectedDestinations' => $selectedDestinations,
            // ]);

            // Simpan paket wisata ke database
            $package = PackageTwoDay::create([
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
                $hotels
            );

            // Simpan harga ke database (dalam format JSON)
            $package->prices()->create([
                'price_data' => json_encode($prices),
            ]);
            // Log::info('Prices saved to database.', ['prices' => $prices]);
            Log::info('Prices saved to database successfully!');

            // Kirim notifikasi berhasil
            $notification = [
                'message' => 'Package generated successfully!',
                'alert-type' => 'success',
            ];
            Log::info('Notification prepared.', ['notification' => $notification]);

            return redirect()->route('all.twoday.packages')->with($notification);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in generateCodeTwoday.', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error in generateCodeTwoday.', ['message' => $e->getMessage()]);
            return redirect()->back()->with([
                'message' => 'An error occurred while generating package.',
                'alert-type' => 'error'
            ])->withInput();
        }
    }


    public function EditGenerateTwodayPackage($id){

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

    public function UpdateGenerateCodeTwoday(Request $request, $id){
        try {

            Log::info('UpdateGenerateCodeTwoday method initiated.');

            // Validasi input
            $validatedData = $request->validate([
                'NamePackage' => 'required|string|max:255',
                'cityOrDistrict_id' => 'required|exists:regencies,id',
                'statusPackage' => 'required|boolean',
                'facilities' => 'required|array',
                'facilities.*' => 'exists:facilities,id',
                'NameAgen' => 'required|exists:users,id',
                'destinations' => 'required|array',
                'destinations.*' => 'exists:destinations,id',
            ]);
            Log::info('Validation passed.', ['validated_data' => $validatedData]);

            // Cari data package berdasarkan ID
            $package = PackageTwoDay::find($id);

            if (!$package) {
                return redirect()->route('all.twoday.packages')->with('error', 'Package not found!');
            }

            // Ambil data dari request
            $namePackage = $request->input('NamePackage');
            $regencyId = $request->input('cityOrDistrict_id');
            $agenId = $request->input('NameAgen');
            $statusPackage = $request->input('statusPackage');
            $information = $request->input('information', '');
            $destinationIds = $request->input('destinations');
            $facilityIds = $request->input('facilities');


            $vehicles = Vehicle::all();
            $hotels = Hotel::where('status', 1)->where('regency_id', $regencyId)->get();
            $meals = Meal::where('duration', '2')->first();
            $crewData = Crew::all();
            $serviceFee = ServiceFee::where('duration', '2')->first()->mark ?? 0.14;
            $feeAgen = AgenFee::find(1)->price ?? 50000;
            $reserveFees = ReserveFee::where('duration', '2')->get();
            $selectedDestinations = Destination::whereIn('id', $destinationIds)
                ->where('regency_id', $regencyId)
                ->get();
            $selectedFacilities = Facility::whereIn('id', $facilityIds)
                ->where('regency_id', $regencyId)
                ->get();

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
                $hotels
            );

            // Update harga di database (dalam format JSON)
            $package->prices()->update([
                'price_data' => json_encode($prices),
            ]);
            //Log::info('Prices update to database.', ['prices' => $prices]);
            Log::info('Prices update to database successfully!');

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
                'message' => 'An error occurred while update generating package.',
                'alert-type' => 'error'
            ])->withInput();
        }
    }

    private function calculatePrices($vehicles, $meals, $crewData, $serviceFee, $feeAgen, $reserveFees, $selectedDestinations,$selectedFacilities, $hotels ){
        $prices = [];

        for ($participants = 1; $participants <= 55; $participants++) {
            // Pilih kendaraan berdasarkan jumlah peserta
            $vehicle = $vehicles->firstWhere(fn($v) => $participants >= $v->capacity_min && $participants <= $v->capacity_max);
            if (!$vehicle) {
                continue;
            }

            $transportCost = $vehicle->price * 2;
            $crew = $crewData->firstWhere(fn($c) => $participants >= $c->min_participants && $participants <= $c->max_participants);

            // Biaya destinasi dan parkir
            $totalCostWNI = 0;
            $totalCostWNA = 0;
            $parkingCost = 0;

            foreach ($selectedDestinations as $destination) {
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

            // Perhitungan biaya fasilitas
            $ShuttleCost = 0;
            $flatCost = 0;
            $facPerdayCost = 0;
            $facPerpersonCost = 0;
            $facInfoCost = 0;
            $facDocCost = 0;
            $guideCost = 0;

            foreach ($selectedFacilities as $facility) {

                if ($facility->type === 'flat') {
                    // Hitung biaya flat
                    $groupCount = ceil($participants / $facility->max_user);
                    $flatCost += $groupCount * $facility->price;

                    // Hitung biaya shuttle berdasarkan kondisi yang ada
                    if ($facility->type === 'shuttle' && $participants >= 18 && $participants <= 55) {
                        $groupCount = ceil($participants / $facility->max_user);
                        $ShuttleCost += $groupCount * $facility->price;
                    }
                } else {
                    // Jika bukan flat, dan memenuhi syarat peserta, kalikan 2
                    if ($facility->type === 'shuttle' && $participants >= 18 && $participants <= 55) {
                        $groupCount = ceil($participants / $facility->max_user);
                        $ShuttleCost += $groupCount * $facility->price * 2;
                    }
                }

                if ($facility->type === 'per_day') {
                    $facPerdayCost += $facility->price * 2;
                }

                if ($facility->type === 'doc' && $participants >= 20 && $participants <= 55) {
                    $facDocCost += $facility->price * 2;
                }

                if ($facility->type === 'tl' && $participants >= 20 && $participants <= 55) {
                    $groupCount = ceil($participants / $facility->max_user);
                    $guideCost += $groupCount * $facility->price * 2;
                }

                if ($facility->type === 'per_person') {
                    $facPerpersonCost += $facility->price * $participants * 2;
                }

                if ($facility->type === 'info') {
                    $facInfoCost += $facility->price * 2;
                }
            }

            $totalFacilityCost = $ShuttleCost + $flatCost + $facPerdayCost + $facPerpersonCost + $facDocCost + $facInfoCost + $guideCost;


            // Biaya tambahan untuk WNA
            $priceDifference = ($totalCostWNA - $totalCostWNI) / $participants;

            // Biaya tambahan lainnya
            $mealCost = $meals ? $meals->price * $meals->num_meals * ($participants + $crew->num_crew) : 0;
            $reserveFee = $reserveFees->firstWhere(fn($r) => $participants >= $r->min_user && $participants <= $r->max_user);
            $reserveFeeCost = $reserveFee ? $reserveFee->price * $participants * 2 : 0;

            // Hitung harga untuk setiap jenis akomodasi
            $priceRow = [
                'vehicle' => $vehicle->name,
                'user' => $participants,
                'wnaCost' => round($priceDifference, 2), // Tambahkan biaya tambahan untuk WNA
            ];

            foreach ($hotels as $hotel) {
                // Validasi tipe hotel
                if (!in_array($hotel->type, ['TwoStar','ThreeStar','FourStar','FiveStar','Villa','Homestay','Cottage','Cabin','Guesthouse','WithoutAccomodation'])) {
                    Log::warning('Invalid hotel type detected', [
                        'hotel' => $hotel->name,
                        'type' => $hotel->type,
                    ]);
                    continue; // Lewatkan iterasi jika tipe hotel tidak valid
                }

                $numRooms = intdiv($participants, 2); // Bagi dua peserta untuk menghitung kamar
                $extraBedCost = 0;

                // Jika jumlah peserta ganjil, hitung biaya extrabed
                if ($participants % 2 !== 0) {
                    $numRooms += 1;
                    $extraBedCost = $hotel->extrabed_price; // Ambil harga extrabed dari database
                }

                $hotelCost = ($hotel->price * $numRooms) + $extraBedCost;

                $totalCost = $totalCostWNI + $transportCost + ($feeAgen * $participants * 2) + $hotelCost + $mealCost + $reserveFeeCost + $parkingCost + $totalFacilityCost;

                $pricePerPerson = $totalCost / $participants;
                $serviceFeeCost = $pricePerPerson * $serviceFee;
                $finalPrice = $pricePerPerson + $serviceFeeCost;

                $priceRow[$hotel->type] = round($finalPrice, 2);

                // // Logging harga setiap hotel
                //     Log::info('Calculated price for hotel', [
                //         'hotel' => $hotel->name,
                //         'type' => $hotel->type,
                //         'participants' => $participants,
                //         'finalPrice' => $finalPrice,
                //         'pricePerPerson' => $pricePerPerson,
                // ]);
            }

            $prices[] = $priceRow;

            // // Logging hasil untuk setiap peserta
                // Log::info('Calculated prices for participants', [
                //     'participants' => $participants,
                //     'priceRow' => $priceRow,
            // ]);
        }

        return $prices;
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

            // Hapus relasi fasilitas (pivot table)
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
