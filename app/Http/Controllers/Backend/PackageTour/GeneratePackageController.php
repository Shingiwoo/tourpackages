<?php

namespace App\Http\Controllers\Backend\PackageTour;

use App\Models\Crew;
use App\Models\Meal;
use App\Models\Vehicle;
use App\Models\AgenFee;
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


    public function generateCode(Request $request){
        $destinations = $request->input('destinations'); // Daftar destinasi dari frontend
        $vehicles = Vehicle::all();
        $meals = Meal::where('duration', '1')->first();
        $crewData = Crew::all();
        $serviceFee = ServiceFee::where('duration','1')->first(); // 14%
        $feeAgen = AgenFee::find(1);
        $reserveFee = 25000;

        // Validasi input destinasi
        if (!$destinations || !is_array($destinations)) {
            return back()->with('error', 'Please select valid destinations.');
        }

        // Ambil data destinasi dari database
        $selectedDestinations = Destination::whereIn('id', $destinations)->get();

        // // Hapus data lama untuk regenerasi
        // Package::truncate();

        for ($participants = 1; $participants <= 45; $participants++) {
            $vehicle = $vehicles->firstWhere(fn($v) => $participants >= $v->min && $participants <= $v->max);
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

            $totalCost = $destinationCost + $transportCost + ($feeAgen * $participants) +
                $mealCost + ($reserveFee * $participants * 1) + $parkingCost;

            $pricePerPerson = $totalCost / $participants;
            $finalPrice = $pricePerPerson + ($pricePerPerson * $serviceFee);

            // Simpan ke database
            PackageOneDay::create([
                'name' => 'Paket Wisata Jogja 1 Hari - A',
                'participants' => $participants,
                'vehicle' => $vehicle->name,
                'price_per_person' => round($finalPrice, 2),
                'total_price' => round($finalPrice * $participants, 2),
                'destinations' => $selectedDestinations->pluck('name'), // Simpan nama destinasi dalam JSON
            ]);
        }

        return redirect()->route('all.packages')->with('message', 'Packages generated successfully!');
    }

}
