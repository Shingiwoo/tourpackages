<?php

namespace App\Http\Controllers\Backend\Rent;

use App\Models\User;
use App\Models\Facility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RentController extends Controller
{
    public function RentIndex()
    {

        $agens = User::where('role', 'agen')->get();
        $allRents = Facility::where('type', 'flat')->get();
        return view('admin.rent.index', compact('allRents', 'agens'));
    }


}
