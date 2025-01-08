<?php

namespace App\Http\Controllers\Agen\Core;

use Illuminate\Http\Request;
use App\Models\PackageOneDay;
use App\Models\PackageTwoDay;
use App\Models\PackageFourDay;
use App\Models\PackageThreeDay;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class PackageServiceController extends Controller
{
    public function AllPackage()
    {
        $agen = Auth::user();

        $packOneday = PackageOneDay::where('agen_id', $agen->id)->with(['destinations', 'prices'])->get();
        $packTwoday = PackageTwoDay::where('agen_id', $agen->id)->with(['destinations', 'prices'])->get();
        $packThreeday = PackageThreeDay::where('agen_id', $agen->id)->with(['destinations', 'prices'])->get();
        $packFourday = PackageFourDay::where('agen_id', $agen->id)->with(['destinations', 'prices'])->get();

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

        // Paginate manual
        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 100;
        $paginatedPackages = new LengthAwarePaginator(
            $allPackages->forPage($currentPage, $perPage),
            $allPackages->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        return view('agen.package.all_package', compact('paginatedPackages', 'countOneday', 'countTwoday', 'countThreeday', 'countFourday'));
    }
}
