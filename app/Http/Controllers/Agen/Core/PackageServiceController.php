<?php

namespace App\Http\Controllers\Agen\Core;

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
        $agen = Auth::user();

        $packOneday = PackageOneDay::where('agen_id', $agen->id)->get();
        $packTwoday = PackageTwoDay::where('agen_id', $agen->id)->get();
        $packThreeday = PackageThreeDay::where('agen_id', $agen->id)->get();
        $packFourday = PackageFourDay::where('agen_id', $agen->id)->get();

        $countOneday = PackageOneDay::countByAgen($agen->id);
        $countTwoday = PackageTwoDay::countByAgen($agen->id);
        $countThreeday = PackageThreeDay::countByAgen($agen->id);
        $countFourday = PackageFourDay::countByAgen($agen->id);

        // Gabungkan semua paket menjadi satu koleksi
        $allPackages = collect()
            ->merge($packOneday)
            ->merge($packTwoday)
            ->merge($packThreeday)
            ->merge($packFourday);

        return view('agen.package.all_package', compact('allPackages', 'countOneday', 'countTwoday', 'countThreeday', 'countFourday'));
    }

    public function PackageShow($id, $type)
    {
        $agen = Auth::user();
        $package = null;

        // Periksa tipe dan cari di tabel yang sesuai
        switch ($type) {
            case 'oneday':
                $package = PackageOneDay::where('agen_id', $agen->id)
                    ->with(['destinations', 'prices', 'regency'])
                    ->where('id', $id)
                    ->first();
                break;

            case 'twoday':
                $package = PackageTwoDay::where('agen_id', $agen->id)
                    ->with(['destinations', 'prices', 'regency'])
                    ->where('id', $id)
                    ->first();
                break;

            case 'threeday':
                $package = PackageThreeDay::where('agen_id', $agen->id)
                    ->with(['destinations', 'prices', 'regency'])
                    ->where('id', $id)
                    ->first();
                break;

            case 'fourday':
                $package = PackageFourDay::where('agen_id', $agen->id)
                    ->with(['destinations', 'prices', 'regency'])
                    ->where('id', $id)
                    ->first();
                break;

            default:
                // Abort jika tipe tidak valid
                abort(404, 'Tipe paket tidak valid');
        }

        // Abort jika paket tidak ditemukan
        if (!$package) {
            abort(404, 'Paket tidak ditemukan');
        }

        return view('agen.package.show_package', compact('package'));
    }


}
