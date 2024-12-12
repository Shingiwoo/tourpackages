<?php

namespace App\Http\Controllers\Backend\Agen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgenController extends Controller
{
    public function AgenDashboard()
    {
        return view('agen.index');
    }
}
