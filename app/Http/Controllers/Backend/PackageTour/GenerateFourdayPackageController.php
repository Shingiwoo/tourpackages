<?php

namespace App\Http\Controllers\Backend\PackageTour;

use App\Models\Crew;
use App\Models\Meal;
use App\Models\User;
use App\Models\Hotel;
use App\Models\AgenFee;
use App\Models\Regency;
use App\Models\Vehicle;
use App\Models\ReserveFee;
use App\Models\ServiceFee;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\PackageFourDay;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class GenerateFourdayPackageController extends Controller
{
    public function AllFourDayPackage(Request $request){

        $packages = PackageFourDay::all();
        $destinations = Destination::all(); // Untuk form generate
        $active = PackageFourDay::where('status', 1)->count();
        $inactive = PackageFourDay::where('status', 0)->count();
        $agens = User::agen()->get();
        return view('admin.package.four.all_packages', compact('packages', 'destinations', 'active', 'inactive', 'agens'));
    }

    public function GenerateFourDayPackage(Request $request){

        $destinations = Destination::all();
        $agens = User::agen()->get();
        $regencies = Regency::all();

        return view('admin.package.four.generate_package_fourday', compact('destinations', 'agens', 'regencies'));
    }

    public function generateCodeFourDay(Request $request){
        try {
            Log::info('generateCodeFourDay method initiated.');

            // Validasi input
            $validatedData = $request->validate([
                'NamePackage' => 'required|string|max:255',
                'cityOrDistrict_id' => 'required|exists:regencies,id',
                'statusPackage' => 'required|boolean',
                'NameAgen' => 'required|exists:users,id',
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

            // Log::info('Input data processed.', [
            //     'namePackage' => $namePackage,
            //     'regencyId' => $regencyId,
            //     'agenId' => $agenId,
            //     'statusPackage' => $statusPackage,
            //     'destinationIds' => $destinationIds,
            // ]);

            // Ambil data terkait
            $vehicles = Vehicle::all();
            $hotels = Hotel::all();
            $meals = Meal::where('duration', '4')->first();
            $crewData = Crew::all();
            $serviceFee = ServiceFee::where('duration', '4')->first()->mark ?? 0.14;
            $feeAgen = AgenFee::find(1)->price ?? 50000;
            $reserveFees = ReserveFee::where('duration', '4')->get();
            $selectedDestinations = Destination::whereIn('id', $destinationIds)->get();

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
            $package = PackageFourDay::create([
                'name_package' => $namePackage,
                'regency_id' => $regencyId,
                'agen_id' => $agenId,
                'status' => $statusPackage,
                'information' => $information,
            ]);
            //Log::info('Package saved to database.', ['package' => $package]);
            Log::info('Package saved to database successfully!');

            // Simpan destinasi untuk paket
            $package->destinations()->sync($destinationIds);

            // Hitung harga per jenis hotel dan jumlah peserta
            $prices = $this->calculatePrices(
                $vehicles,
                $meals,
                $crewData,
                $serviceFee,
                $feeAgen,
                $reserveFees,
                $selectedDestinations,
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

            return redirect()->route('all.fourday.packages')->with($notification);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in generateCodeFourDay.', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error in generateCodeFourDay.', ['message' => $e->getMessage()]);
            return redirect()->back()->with([
                'message' => 'An error occurred while generating package.',
                'alert-type' => 'error'
            ])->withInput();
        }
    }


    public function EditGenerateFourDayPackage($id){

        $package = PackageFourDay::with('destinations')->find($id);

        if (!$package) {
            return redirect()->route('all.packages')->with('error', 'Package not found!');
        }

        $destinations = Destination::all();
        $selectedDestinations = $package->destinations->pluck('id')->toArray();
        $agens = User::agen()->get();
        $regencies = Regency::all();

        return view('admin.package.four.edit_package', compact('destinations', 'agens', 'regencies', 'package', 'selectedDestinations'));
    }

    public function UpdateGenerateCodeFourDay(Request $request, $id){
        try {

            Log::info('UpdateGenerateCodeFourDay method initiated.');

            // Validasi input
            $validatedData = $request->validate([
                'NamePackage' => 'required|string|max:255',
                'cityOrDistrict_id' => 'required|exists:regencies,id',
                'statusPackage' => 'required|boolean',
                'NameAgen' => 'required|exists:users,id',
                'destinations' => 'required|array',
                'destinations.*' => 'exists:destinations,id',
            ]);
            Log::info('Validation passed.', ['validated_data' => $validatedData]);

            // Cari data package berdasarkan ID
            $package = PackageFourDay::find($id);

            if (!$package) {
                return redirect()->route('all.fourday.packages')->with('error', 'Package not found!');
            }

            // Ambil data dari request
            $namePackage = $request->input('NamePackage');
            $regencyId = $request->input('cityOrDistrict_id');
            $agenId = $request->input('NameAgen');
            $statusPackage = $request->input('statusPackage');
            $information = $request->input('information', '');
            $destinationIds = $request->input('destinations');

            $vehicles = Vehicle::all();
            $hotels = Hotel::all();
            $meals = Meal::where('duration', '4')->first();
            $crewData = Crew::all();
            $serviceFee = ServiceFee::where('duration', '4')->first()->mark ?? 0.14;
            $feeAgen = AgenFee::find(1)->price ?? 50000;
            $reserveFees = ReserveFee::where('duration', '4')->get();
            $selectedDestinations = Destination::whereIn('id', $destinationIds)->get();

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

            // Hitung harga per jenis hotel dan jumlah peserta
            $prices = $this->calculatePrices(
                $vehicles,
                $meals,
                $crewData,
                $serviceFee,
                $feeAgen,
                $reserveFees,
                $selectedDestinations,
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

            return redirect()->route('all.fourday.packages')->with($notification);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in UpdateGenerateCodeFourDay.', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error in UpdateGenerateCodeFourDay.', ['message' => $e->getMessage()]);
            return redirect()->back()->with([
                'message' => 'An error occurred while update generating package.',
                'alert-type' => 'error'
            ])->withInput();
        }
    }

    private function calculatePrices($vehicles, $meals, $crewData, $serviceFee, $feeAgen, $reserveFees, $selectedDestinations, $hotels){
        $prices = [];

        for ($participants = 1; $participants <= 45; $participants++) {
            // Pilih kendaraan berdasarkan jumlah peserta
            $vehicle = $vehicles->firstWhere(fn($v) => $participants >= $v->capacity_min && $participants <= $v->capacity_max);
            if (!$vehicle) {
                continue;
            }

            $transportCost = $vehicle->price * 4;
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

            // Biaya tambahan lainnya
            $mealCost = $meals ? $meals->price * $meals->num_meals * ($participants + $crew->num_crew) : 0;
            $reserveFee = $reserveFees->firstWhere(fn($r) => $participants >= $r->min_user && $participants <= $r->max_user);
            // biaya cadangan harga x jumlah peserta * 3 hari
            $reserveFeeCost = $reserveFee ? $reserveFee->price * $participants * 4 : 0;

            // Biaya tambahan untuk WNA
            $priceDifference = ($totalCostWNA - $totalCostWNI) / $participants;

            // // Logging
            // Log::info('Calculated mealCost', [
            //     'mealCost' => $mealCost,
            //     'reserveFeeCost' => $reserveFeeCost,
            // ]);

            // Hitung harga untuk setiap jenis akomodasi
            $priceRow = [
                'vehicle' => $vehicle->name,
                'user' => $participants,
                'wnaCost' => round($priceDifference, 2), // Tambahkan biaya tambahan untuk WNA
            ];

            foreach ($hotels as $hotel) {
                $numRooms = intdiv($participants, 2); // Bagi dua peserta untuk menghitung kamar
                $extraBedCost = 0;

                // Jika jumlah peserta ganjil, tambahkan 1 kamar dan hitung biaya extrabed
                if ($participants % 2 !== 0) {
                    $numRooms += 1;
                    $extraBedCost = $hotel->extrabed_price * 3; // Ambil harga extrabed x 3 malam
                }
                // harga hotel x jumlah kamar x 3 malam + extrabed
                $hotelCost = ($hotel->price * $numRooms * 3) + $extraBedCost;

                // fee agen x jumlah peserta x jumlah hari 4
                $totalCost = $totalCostWNI + $transportCost + ($feeAgen * $participants * 4) + $hotelCost + $mealCost + $reserveFeeCost + $parkingCost;

                $pricePerPerson = $totalCost / $participants;
                $serviceFeeCost = $pricePerPerson * $serviceFee;
                $finalPrice = $pricePerPerson + $serviceFeeCost;

                $priceRow[$hotel->type] = round($finalPrice, 2);
            }

            $prices[] = $priceRow;
        }

        return $prices;
    }


    public function AllFourDayPackagesAgen($id)
    {
        // Ambil data agen berdasarkan ID
        $agen = User::where('role', 'agen')->where('id', $id)->first();

        if (!$agen) {
            return redirect()->back()->with('error', 'Agen not found!');
        }

        // Ambil semua paket yang dimiliki oleh agen ini
        $packages = PackageFourDay::where('agen_id', $id)->with(['destinations', 'prices'])->paginate(5);

        // Ambil data destinasi dan kabupaten untuk dropdown (opsional)
        $destinations = Destination::all();
        $regencies = Regency::all();

        return view('admin.package.four.data_package', compact('destinations', 'regencies', 'packages', 'agen'));
    }

    public function PackageFourDayShow($id)
    {
        // Ambil data paket berdasarkan ID, termasuk relasi
        $package = PackageFourDay::with(['destinations', 'prices', 'regency'])->findOrFail($id);

        // Ambil data destinasi dan kabupaten untuk dropdown (opsional)
        $destinations = Destination::all();
        $regencies = Regency::all();

        return view('admin.package.four.show_package', compact('destinations', 'regencies', 'package'));
    }


    public function DeleteFourDayPackage($id)
    {
        try {
            // Cari paket berdasarkan ID
            $package = PackageFourDay::find($id);

            if (!$package) {
                return redirect()->route('all.fourday.packages')->with('error', 'Package not found!');
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
