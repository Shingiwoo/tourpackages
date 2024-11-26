<?php

namespace App\Http\Controllers\Backend\Vehicle;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function AllVehicles()
    {

        $vehicles = Vehicle::latest()->get();
        $statAct = Vehicle::where('status', 1)->count();
        $statInact = Vehicle::where('status', 0)->count();
        $cityCar = Vehicle::where('type', 'City Car')->count();
        $miniBus = Vehicle::where('type', 'Mini Bus')->count();
        $bus = Vehicle::where('type', 'Bus')->count();
        return view('admin.vehicles.index', compact('vehicles', 'cityCar', 'miniBus', 'bus', 'statAct', 'statInact'));
    }
}
