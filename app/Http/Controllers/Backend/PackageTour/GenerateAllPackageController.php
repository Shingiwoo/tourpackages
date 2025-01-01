<?php

namespace App\Http\Controllers\Backend\PackageTour;

use App\Models\User;
use App\Models\Regency;
use App\Models\Facility;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GenerateAllPackageController extends Controller
{
    public function GeneratePackages(Request $request)
    {

        $destinations = Destination::all();
        $agens = User::agen()->get();
        $regencies = Regency::all();
        $facilities = Facility::all();

        return view('admin.package.generate_all_packages', compact('destinations', 'agens', 'regencies', 'facilities'));
    }

    public function generate(Request $request)
    {
        $duration = $request->input('DurationPackage');

        switch ($duration) {
            case '1':
                return app(GeneratePackageController::class)->generateCodeOneday($request);
            case '2':
                return app(GenerateTwodayPackageController::class)->generateCodeTwoday($request);
            case '3':
                return app(GenerateThreedayPackageController::class)->generateCodeThreeday($request);
            case '4':
                return app(GenerateFourdayPackageController::class)->generateCodeFourday($request);
            default:
                return redirect()->back()->with([
                    'message' => 'Invalid duration selected!',
                    'alert-type' => 'error',
                ])->withInput();
        }
    }

}
