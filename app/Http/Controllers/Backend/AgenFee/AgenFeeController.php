<?php

namespace App\Http\Controllers\Backend\AgenFee;

use App\Http\Controllers\Controller;
use App\Models\AgenFee;
use Illuminate\Http\Request;

class AgenFeeController extends Controller
{
    public function AllAgenFee()
    {
        $agenFee = AgenFee::find(1);
        return view('admin.agen.all_agen_fee', compact('agenFee'));
    }

    public function UpdateAgenFee(Request $request){

        $agenFeeID = $request->id;

        // Validasi data input
        $validatedData = $request->validate([
            'agenFee' => 'required',
        ]);

        // Format harga: hapus koma sebelum disimpan
        $validatedData['agenFee'] = str_replace(',', '', $validatedData['agenFee']);

        // Temukan data berdasarkan ID
        $dataAgenFee = AgenFee::findOrFail($agenFeeID);

        // Update data
        $dataAgenFee->update([
            'price' => $validatedData['agenFee'],
        ]);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Agen Fee Data Updated',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.agen.fee')->with($notification);
    }
}
