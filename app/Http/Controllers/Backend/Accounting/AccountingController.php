<?php

namespace App\Http\Controllers\Backend\Accounting;

use App\Http\Controllers\Controller;

class AccountingController extends Controller
{
    public function index()
    {
        return view('admin.accounting.index');
    }
}
