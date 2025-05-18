<?php

namespace App\Http\Controllers\Backend\Accounting;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::all();
        return view('admin.account.index', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Log::info('Request Data:', $request->all());

        // Validasi data input
        $validatedData = $request->validate([
            'AccountCode' => 'required|string',
            'AccountName' => 'required|string|max:255',
            'AccountType' => 'required|string',
            'AccountCategory' => 'required|string',
        ]);

        //Log::info('Check Data Validasi:', $validatedData);

        // Mapping nama input form ke nama kolom database
        $accountData = [
            'code' => $validatedData['AccountCode'],
            'name' => $validatedData['AccountName'],
            'type' => $validatedData['AccountType'],
            'category' => $validatedData['AccountCategory'],
        ];

        // Buat data baru di database
        Account::create($accountData);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Account Data Saved',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.accounts')->with($notification);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       // Validasi data input
        $validatedData = $request->validate([
            'AccountCode' => 'required|string',
            'AccountName' => 'required|string|max:255',
            'AccountType' => 'required|string',
            'AccountCategory' => 'required|string',
        ]);

        // Temukan data berdasarkan ID
        $accountData = Account::findOrFail($id);

        // Update data
        $accountData->update([
            'code' => $validatedData['AccountCode'],
            'name' => $validatedData['AccountName'],
            'type' => $validatedData['AccountType'],
            'category' => $validatedData['AccountCategory'],
        ]);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Account Fee Data Updated',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.accounts')->with($notification);
    }
}
