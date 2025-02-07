<?php

namespace App\Http\Controllers\Backend\PackageTour;

use App\Models\Crew;
use App\Models\Meal;
use App\Models\User;
use App\Models\AgenFee;
use App\Models\Regency;
use App\Models\Vehicle;
use App\Models\ReserveFee;
use App\Models\ServiceFee;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\PackageOneDay;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Facility;

class GeneratePackageController extends Controller
{
    public function AllPackage(Request $request)
    {

        $packages = PackageOneDay::all();
        $destinations = Destination::all(); // Untuk form generate
        $active = PackageOneDay::where('status', 1)->count();
        $inactive = PackageOneDay::where('status', 0)->count();
        $agens = User::where('role', 'agen')->get();
        return view('admin.package.oneday.all_packages', compact('packages', 'destinations', 'active', 'inactive', 'agens'));
    }

    public function GeneratePackage(Request $request)
    {

        $destinations = Destination::all();
        $agens = User::agen()->get();
        $regencies = Regency::all();
        $facilities = Facility::all();

        return view('admin.package.oneday.generate_package_oneday', compact('destinations', 'agens', 'regencies', 'facilities'));
    }

    public function generateCodeOneday(Request $request){
        try {
            Log::info('generateCodeOneday method initiated.');

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
            $vehicles = Vehicle::getByRegency($regencyId);
            $meals = Meal::where('duration', '1')->first();
            $crewData = Crew::all();
            $serviceFee = ServiceFee::where('duration', '1')->first()->mark ?? 0.14;
            $feeAgen = AgenFee::find(1)->price ?? 50000;
            $reserveFees = ReserveFee::all();
            $selectedDestinations = Destination::whereIn('id', $destinationIds)
                ->where('regency_id', $regencyId)
                ->get();
            $selectedFacilities = Facility::whereIn('id', $facilityIds)
                ->where('regency_id', $regencyId)
                ->get();

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
            $package = PackageOneDay::create([
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

            // Hitung harga per orang dan jumlah peserta
            $prices = $this->calculatePrices(
                $vehicles,
                $meals,
                $crewData,
                $serviceFee,
                $feeAgen,
                $reserveFees,
                $selectedDestinations,
                $selectedFacilities
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

            return redirect()->route('all.packages')->with($notification);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in generateCodeOneday.', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error in generateCodeOneday.', ['message' => $e->getMessage()]);
            return redirect()->back()->with([
                'message' => 'An error occurred while generating package.',
                'alert-type' => 'error'
            ])->withInput();
        }
    }


    public function EditGeneratePackage($id){

        $package = PackageOneDay::with('destinations')->find($id);

        if (!$package) {
            return redirect()->route('all.packages')->with('error', 'Package not found!');
        }

        $destinations = Destination::all();
        $selectedDestinations = $package->destinations->pluck('id')->toArray();
        $facilities = Facility::all();
        $selectedFacilities = $package->facilities->pluck('id')->toArray();
        $agens = User::agen()->get();
        $regencies = Regency::all();

        return view('admin.package.oneday.edit_package', compact('destinations', 'agens', 'regencies', 'package', 'selectedDestinations', 'facilities', 'selectedFacilities'));
    }

    public function UpdateGenerateCodeOneday(Request $request, $id){
        try {

            Log::info('UpdateGenerateCodeOneday method initiated.');

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

            // Cari data package berdasarkan ID
            $package = PackageOneDay::find($id);

            if (!$package) {
                return redirect()->route('all.packages')->with('error', 'Package not found!');
            }

            // Ambil data dari request
            $namePackage = $request->input('NamePackage');
            $regencyId = $request->input('cityOrDistrict_id');
            $agenId = $request->input('NameAgen');
            $statusPackage = $request->input('statusPackage');
            $information = $request->input('information', '');
            $destinationIds = $request->input('destinations');
            $facilityIds = $request->input('facilities');


            // Ambil data terkait
            $vehicles = Vehicle::getByRegency($regencyId);
            $meals = Meal::where('duration', '1')->first();
            $crewData = Crew::all();
            $serviceFee = ServiceFee::where('duration', '1')->first()->mark ?? 0.14;
            $feeAgen = AgenFee::find(1)->price ?? 50000;
            $reserveFees = ReserveFee::all();
            $selectedDestinations = Destination::whereIn('id', $destinationIds)
            ->whereIn('id', Destination::getByRegency($regencyId)->pluck('id'))
            ->get();
            $selectedFacilities = Facility::whereIn('id', $facilityIds)
            ->whereIn('id', Facility::getByRegency($regencyId)->pluck('id'))
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
                $selectedFacilities
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

            return redirect()->route('all.packages')->with($notification);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in UpdateGenerateCodeOneday.', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error in UpdateGenerateCodeOneday.', ['message' => $e->getMessage()]);
            return redirect()->back()->with([
                'message' => 'An error occurred while update generating package.',
                'alert-type' => 'error'
            ])->withInput();
        }
    }

    private function calculatePrices($vehicles, $meals, $crewData, $serviceFee, $feeAgen, $reserveFees, $selectedDestinations, $selectedFacilities) {

        $prices = [];
        for ($participants = 1; $participants <= 55; $participants++) {
            // Cari kendaraan yang cocok
            $vehicle = $vehicles->firstWhere(fn($v) => $participants >= $v->capacity_min && $participants <= $v->capacity_max);
            if (!$vehicle) {
                Log::info('No vehicle found for participants.', ['participants' => $participants]);
                continue;
            }

            $transportCost = $vehicle->price;

            // Cari crew yang cocok
            $crew = $crewData->firstWhere(fn($c) => $participants >= $c->min_participants && $participants <= $c->max_participants);

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
                    'Bus' => $destination->parking_bus
                ];
                $parkingCost += $parkingCosts[$vehicle->type] ?? 0; // Cegah undefined index
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
                if ($facility->type === 'shuttle' && $participants >= 18 && $participants <= 55) {
                    $groupCount = ceil($participants / $facility->max_user);
                    $ShuttleCost += $groupCount * $facility->price;
                }

                if ($facility->type === 'flat') {
                    $groupCount = ceil($participants / $facility->max_user);
                    $flatCost += $groupCount * $facility->price;
                }

                if ($facility->type === 'per_day') {
                    $facPerdayCost += $facility->price;
                }

                if ($facility->type === 'doc' && $participants >= 20 && $participants <= 55) {
                    $facDocCost += $facility->price;
                }

                if ($facility->type === 'tl' && $participants >= 20 && $participants <= 55) {
                    $groupCount = ceil($participants / $facility->max_user);
                    $guideCost += $groupCount * $facility->price;
                }

                if ($facility->type === 'per_person') {
                    $facPerpersonCost += $facility->price * $participants;
                }

                if ($facility->type === 'info') {
                    $facInfoCost += $facility->price;
                }
            }

            $totalFacilityCost = $ShuttleCost + $flatCost + $facPerdayCost + $facPerpersonCost + $facDocCost + $facInfoCost + $guideCost;

            // Hitung total biaya
            $priceDifference = ($totalCostWNA - $totalCostWNI) / $participants;
            $mealCost = $meals ? $meals->price * ($participants + ($crew->num_crew ?? 0)) : 0;
            $mealCrew = $meals ? $meals->price * ($crew->num_crew ?? 0) : 0;
            $reserveFee = $reserveFees->firstWhere(fn($r) => $participants >= $r->min_user && $participants <= $r->max_user);
            $reserveFeeCost = $reserveFee ? $reserveFee->price * $participants : 0;

            $totalCost = $totalCostWNI + $transportCost + ($feeAgen * $participants) +
                $mealCost + $reserveFeeCost + $parkingCost + $totalFacilityCost;

            $totalNoMeal = $totalCostWNI + $transportCost + ($feeAgen * $participants) +
                $mealCrew + $reserveFeeCost + $parkingCost + $totalFacilityCost;

            $noMeal = $totalNoMeal / $participants;
            $pricePerPerson = $totalCost / $participants;
            $serviceFeeCost = $pricePerPerson * $serviceFee;

            $finalPrice = $pricePerPerson + $serviceFeeCost;
            $noMealPrice = $noMeal + $serviceFeeCost;

            // Log::info('Cost data.', [
            //     'participants' => $participants,
            //     'totalCostWNI' => $totalCostWNI,
            //     'transportCost' => $transportCost,
            //     'feeAgen' => $feeAgen,
            //     'mealCost' => $mealCost,
            //     'reserveFeeCost' => $reserveFeeCost,
            //     'parkingCost' => $parkingCost,
            //     'totalFacilityCost' => $totalFacilityCost,
            //     'totalFacilityCost' => $totalFacilityCost,
            //     'totalCost' => $totalCost,
            //     'pricePerPerson' => $pricePerPerson,
            //     'serviceFeeCost' => $serviceFeeCost,
            //     'finalPrice' => $finalPrice
            // ]);

            $prices[] = [
                'vehicle' => $vehicle->name,
                'user' => $participants,
                'price' => round($finalPrice, 2),
                'nomeal' => round($noMealPrice, 2),
                'wnaCost' => $priceDifference,
            ];
        }

        return $prices;
    }



    public function AllPackagesAgen($id)
    {
        // Ambil data agen berdasarkan ID
        $agen = User::where('role', 'agen')->where('id', $id)->first();

        if (!$agen) {
            return redirect()->back()->with('error', 'Agen not found!');
        }

        // Ambil semua paket yang dimiliki oleh agen ini
        $packages = PackageOneDay::where('agen_id', $id)->with(['destinations', 'prices'])->paginate(4);

        // Ambil data destinasi dan kabupaten untuk dropdown (opsional)
        $destinations = Destination::all();
        $facilities = Facility::all();
        $regencies = Regency::all();

        return view('admin.package.oneday.data_package', compact('destinations', 'regencies', 'packages', 'agen', 'facilities'));
    }

    public function PackageShow($id)
    {
        // Ambil data paket berdasarkan ID, termasuk relasi
        $package = PackageOneDay::with(['destinations', 'prices', 'regency'])->findOrFail($id);

        // Ambil data destinasi dan kabupaten untuk dropdown (opsional)
        $destinations = Destination::all();
        $facilities = Facility::all();
        $regencies = Regency::all();

        return view('admin.package.oneday.show_package', compact('destinations', 'regencies', 'package', 'facilities'));
    }


    public function DeletePackage($id)
    {
        try {
            // Cari paket berdasarkan ID
            $package = PackageOneDay::find($id);

            if (!$package) {
                return redirect()->route('all.packages')->with('error', 'Package not found!');
            }

            // Hapus relasi destinasi (pivot table)
            $package->destinations()->detach();

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
