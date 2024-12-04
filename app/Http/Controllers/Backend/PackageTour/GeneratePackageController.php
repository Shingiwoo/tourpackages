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
use App\Http\Controllers\Controller;

class GeneratePackageController extends Controller
{
    public function AllPackage(Request $request){

        $packages = PackageOneDay::all();
        $destinations = Destination::all(); // Untuk form generate
        return view('admin.package.oneday.all_packages', compact('packages', 'destinations'));
    }

    public function GeneratePackage(Request $request){

        $destinations = Destination::all();
        $agens = User::where('role','agen')->where('status', 'active')->get();
        $regencies = Regency::all();

        return view('admin.package.oneday.generate_package_oneday', compact('destinations', 'agens', 'regencies'));
    }


    public function generateCode(Request $request){
        $request->validate([
            'NamePackage' => 'required|string|max:255',
            'cityOrDistrict_id' => 'required|exists:regencies,id',
            'statusPackage' => 'required|boolean',
            'NameAgen' => 'required|exists:agens,id',
            'destinations' => 'required|array',
            'destinations.*' => 'exists:destinations,id',
        ]);

        // Ambil data dari request
        $namePackage = $request->input('NamePackage');
        $regencyId = $request->input('cityOrDistrict_id');
        $agenId = $request->input('NameAgen');
        $statusPackage = $request->input('statusPackage');
        $information = $request->input('information', '');
        $destinationIds = $request->input('destinations');

        $vehicles = Vehicle::all();
        $meals = Meal::where('duration', '1')->first();
        $crewData = Crew::all();
        $serviceFee = ServiceFee::where('duration', '1')->first()->mark ?? 0.14;
        $feeAgen = AgenFee::find(1)->price ?? 50000;
        $reserveFees = ReserveFee::all();
        $selectedDestinations = Destination::whereIn('id', $destinationIds)->get();

        // Simpan paket wisata ke database
        $package = PackageOneDay::create([
            'name_package' => $namePackage,
            'regency_id' => $regencyId,
            'agen_id' => $agenId,
            'status' => $statusPackage,
            'information' => $information,
        ]);

        // Simpan destinasi untuk paket
        $package->destinations()->sync($destinationIds);

        // Hitung harga per armada dan jumlah peserta
        $prices = [];
        for ($participants = 1; $participants <= 45; $participants++) {
            $vehicle = $vehicles->firstWhere(fn($v) => $participants >= $v->capacity_min && $participants <= $v->capacity_max);
            if (!$vehicle) continue;

            $transportCost = $vehicle->price;
            $crew = $crewData->firstWhere(fn($c) => $participants >= $c->min_participants && $participants <= $c->max_participants);

            $destinationCost = 0;
            $parkingCost = 0;
            foreach ($selectedDestinations as $destination) {
                if ($destination->price_type == 'per_person') {
                    $destinationCost += $destination->price * $participants;
                } else {
                    $destinationCost += $destination->price;
                }
                $parkingCost += $destination->{"parkir_{$vehicle->type}"};
            }

            $mealCost = $meals ? $meals->price * $participants * $crew->num_crew : 0;

            $reserveFee = $reserveFees->firstWhere(fn($r) => $participants >= $r->min_user && $participants <= $r->max_user);
            $reserveFeeCost = $reserveFee ? $reserveFee->price * $participants : 0;

            $totalCost = $destinationCost + $transportCost + ($feeAgen * $participants) +
                $mealCost + $reserveFeeCost + $parkingCost;

            $pricePerPerson = $totalCost / $participants;
            $finalPrice = $pricePerPerson + ($pricePerPerson * $serviceFee);

            // Simpan harga ke array
            $prices[] = [
                'armada' => $vehicle->name,
                'user' => $participants,
                'price' => round($finalPrice, 2),
            ];
        }

        // Simpan harga ke database (dalam format JSON)
        $package->prices()->create([
            'price_data' => json_encode($prices),
        ]);

        return redirect()->route('all.packages')->with('message', 'Package generated successfully!');
    }


}
