<?php

namespace App\Http\Controllers\Backend\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function indexInvoice()
    {
        return view('admin.invoice.index');
    }


    public function configInvoice()
    {
        return view('admin.invoice.config');
    }


    public function createInvoice()
    {
        return view('admin.invoice.add');
    }
}
