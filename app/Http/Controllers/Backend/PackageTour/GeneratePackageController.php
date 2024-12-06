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
        $active = PackageOneDay::where('status', 1)->count();
        $inactive = PackageOneDay::where('status', 0)->count();
        $agens = User::agen()->get();
        return view('admin.package.oneday.all_packages', compact('packages', 'destinations', 'active', 'inactive', 'agens'));
    }

    public function GeneratePackage(Request $request){

        $destinations = Destination::all();
        $agens = User::agen()->get();
        $regencies = Regency::all();

        return view('admin.package.oneday.generate_package_oneday', compact('destinations', 'agens', 'regencies'));
    }


    public function generateCodeOneday(Request $request){
        $request->validate([
            'NamePackage' => 'required|string|max:255',
            'cityOrDistrict_id' => 'required|exists:regencies,id',
            'statusPackage' => 'required|boolean',
            'NameAgen' => 'required|exists:users,id',
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
                    $destinationCost += $destination->price_wni * $participants;
                } else {
                    $destinationCost += $destination->price_wni;
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
                'vehicle' => $vehicle->name,
                'user' => $participants,
                'price' => round($finalPrice, 2),
            ];
        }

        // Simpan harga ke database (dalam format JSON)
        $package->prices()->create([
            'price_data' => json_encode($prices),
        ]);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Package generated successfully!',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.packages')->with($notification);
    }

    public function EditGeneratePackage($id){

        $package = PackageOneDay::with('destinations')->find($id);

        if (!$package) {
            return redirect()->route('all.packages')->with('error', 'Package not found!');
        }

        $destinations = Destination::all();
        $selectedDestinations = $package->destinations->pluck('id')->toArray();
        $agens = User::agen()->get();
        $regencies = Regency::all();

        return view('admin.package.oneday.edit_package', compact('destinations', 'agens', 'regencies','package', 'selectedDestinations'));
    }

    public function UpdateGenerateCodeOneday(Request $request, $id){

        $request->validate([
            'NamePackage' => 'required|string|max:255',
            'cityOrDistrict_id' => 'required|exists:regencies,id',
            'statusPackage' => 'required|boolean',
            'NameAgen' => 'required|exists:users,id',
            'destinations' => 'required|array',
            'destinations.*' => 'exists:destinations,id',
        ]);

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

        $vehicles = Vehicle::all();
        $meals = Meal::where('duration', '1')->first();
        $crewData = Crew::all();
        $serviceFee = ServiceFee::where('duration', '1')->first()->mark ?? 0.14;
        $feeAgen = AgenFee::find(1)->price ?? 50000;
        $reserveFees = ReserveFee::all();
        $selectedDestinations = Destination::whereIn('id', $destinationIds)->get();

        // Update paket wisata di database
        $package->update([
            'name_package' => $namePackage,
            'regency_id' => $regencyId,
            'agen_id' => $agenId,
            'status' => $statusPackage,
            'information' => $information,
        ]);

        // Update relasi destinasi untuk paket
        $package->destinations()->sync($destinationIds);

        // Hitung ulang harga per armada dan jumlah peserta
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
                    $destinationCost += $destination->price_wni * $participants;
                } else {
                    $destinationCost += $destination->price_wni;
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
                'vehicle' => $vehicle->name,
                'user' => $participants,
                'price' => round($finalPrice, 2),
            ];
        }

        // Update harga di database (dalam format JSON)
        $package->prices()->update([
            'price_data' => json_encode($prices),
        ]);


        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Package updated successfully!',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.packages')->with($notification);
    }

    public function AllPackagesAgen($id){
        // Ambil data agen berdasarkan ID
        $agen = User::where('role', 'agen')->where('id', $id)->first();

        if (!$agen) {
            return redirect()->back()->with('error', 'Agen not found!');
        }

        // Ambil semua paket yang dimiliki oleh agen ini
        $packages = PackageOneDay::where('agen_id', $id)->with(['destinations', 'prices'])->paginate(5);

        // Ambil data destinasi dan kabupaten untuk dropdown (opsional)
        $destinations = Destination::all();
        $regencies = Regency::all();

        return view('admin.package.oneday.data_package', compact('destinations', 'regencies', 'packages', 'agen'));
    }

    public function PackageShow($id){
        // Ambil data paket berdasarkan ID, termasuk relasi
        $package = PackageOneDay::with(['destinations', 'prices', 'regency'])->findOrFail($id);

        // Ambil data destinasi dan kabupaten untuk dropdown (opsional)
        $destinations = Destination::all();
        $regencies = Regency::all();

        return view('admin.package.oneday.show_package', compact('destinations', 'regencies', 'package'));
    }


    public function DeletePackage($id){
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
