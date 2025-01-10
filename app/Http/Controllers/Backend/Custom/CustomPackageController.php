<?php

namespace App\Http\Controllers\Backend\Custom;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Facility;
use App\Models\Regency;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class CustomPackageController extends Controller
{
    public function CustomDashboard()
    {
        $destinations = Destination::all();
        $regencies = Regency::all();
        $vehicles = Vehicle::all();
        $facilities = Facility::all();

        return view('admin.custom.index', compact('destinations', 'regencies', 'vehicles', 'facilities'));
    }
}
