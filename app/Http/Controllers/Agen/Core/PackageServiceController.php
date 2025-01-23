<?php

namespace App\Http\Controllers\Agen\Core;

use App\Models\Custom;
use App\Models\Regency;
use App\Models\PackageOneDay;
use App\Models\PackageTwoDay;
use App\Models\PackageFourDay;
use App\Models\PackageThreeDay;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PackageServiceController extends Controller
{
    public function AllPackage()
    {
        $agenID = Auth::user();

        $packOneday = PackageOneDay::where('agen_id', $agenID->id)->get();
        $packTwoday = PackageTwoDay::where('agen_id', $agenID->id)->get();
        $packThreeday = PackageThreeDay::where('agen_id', $agenID->id)->get();
        $packFourday = PackageFourDay::where('agen_id', $agenID->id)->get();

        // Filter data Custom berdasarkan agen_id di JSON
        $allCustPackages = Custom::whereRaw("JSON_EXTRACT(custompackage, '$.agen_id') = ?", [$agenID->id])->get();

        // Siapkan data Custom dengan format yang sesuai
        $customPackages = $allCustPackages->map(function ($custom) {
            $customPackage = json_decode($custom->custompackage, true);
            $customPackage['id'] = $custom->id; // Tambahkan ID
            $customPackage['name_package'] = $customPackage['package_name']; // Nama paket
            $customPackage['type'] = $customPackage['package_type']; // Jenis paket
            $customPackage['status'] = $customPackage['status'] === 'active'; // Konversi status
            $customPackage['regency'] = Regency::find($customPackage['regency_id']); // Cari data Regency
            return (object) $customPackage; // Ubah menjadi objek untuk keseragaman
        });

        $countOneday = PackageOneDay::countByAgen($agenID->id);
        $countTwoday = PackageTwoDay::countByAgen($agenID->id);
        $countThreeday = PackageThreeDay::countByAgen($agenID->id);
        $countFourday = PackageFourDay::countByAgen($agenID->id);

        // Gabungkan semua paket menjadi satu koleksi
        $allPackages = collect()
            ->merge($packOneday)
            ->merge($packTwoday)
            ->merge($packThreeday)
            ->merge($packFourday)
            ->merge($customPackages); // Gabungkan dengan paket Custom

        return view('agen.package.all_package', compact('allPackages', 'countOneday', 'countTwoday', 'countThreeday', 'countFourday'));
    }

    public function PackageShow($id, $type)
    {
        $agen = Auth::user();
        $package = null;

        $packageTypes = [
            'oneday' => PackageOneDay::class,
            'twoday' => PackageTwoDay::class,
            'threeday' => PackageThreeDay::class,
            'fourday' => PackageFourDay::class,
        ];

        if (array_key_exists($type, $packageTypes)) {
            $package = $packageTypes[$type]::where('agen_id', $agen->id)
                ->with(['destinations', 'prices', 'regency'])
                ->where('id', $id)
                ->first();
        } elseif ($type === 'custom') {
            // Logika untuk custom package
            $custom = Custom::whereRaw("JSON_EXTRACT(custompackage, '$.agen_id') = ?", [$agen->id])
                ->where('id', $id)
                ->first();

            if ($custom) {
                $customPackage = json_decode($custom->custompackage, true);
                $package = (object) [
                    'id' => $custom->id,
                    'type' => 'custom',
                    'name_package' => $customPackage['package_name'] ?? 'Unknown',
                    'duration' => $customPackage['DurationPackage'] ?? '0',
                    'night' => $customPackage['night'],
                    'destinations' => $customPackage['destinationNames'] ?? 'Unknown',
                    'facilities' => $customPackage['facilityNames'] ?? 'Unknown',
                    'participants' => $customPackage['participants'] ?? '0',
                    'costPerPerson' => $customPackage['costPerPerson'] ?? 0,
                    'childCost' => $customPackage['childCost'] ?? 0,
                    'additionalCostWna' => $customPackage['additionalCostWna'] ?? 0,
                    'downPayment' => $customPackage['downPayment'] ?? 0,
                    'remainingCosts' => $customPackage['remainingCosts'] ?? 0,
                    'totalCost' => $customPackage['totalCost'] ?? 0,
                ];
            }
        } else {
            abort(404, 'Tipe paket tidak valid');
        }
        // Abort jika paket tidak ditemukan
        if (!$package) {
            abort(404, 'Paket tidak ditemukan');
        }

        return view('agen.package.show_package', compact('package'));
    }



}
