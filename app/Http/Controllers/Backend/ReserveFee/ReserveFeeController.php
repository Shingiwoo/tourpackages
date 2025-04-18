<?php

namespace App\Http\Controllers\Backend\ReserveFee;

use App\Models\ReserveFee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReserveFeeController extends Controller
{
    public function AllReserveFee()
    {

        $reservefees = ReserveFee::all();

        return view('admin.reservefee.reserve_fee', compact('reservefees'));
    }

    public function StoreReserveFee(Request $request){
        // Validasi data input
        $validatedData = $request->validate([
            'priceReserveFee' => 'required',
            'ReserveFeeDuration' => 'required|in:1,2,3,4,5,6',
            'ReserveFeeMinUser' => 'required',
            'ReserveFeeMaxUser' => 'required',
        ]);

        $validatedData['priceReserveFee'] = str_replace(',', '', $validatedData['priceReserveFee']);

        // Mapping nama input form ke nama kolom database
        $reserveFeeData = [
            'price' => $validatedData['priceReserveFee'],
            'duration' => $validatedData['ReserveFeeDuration'],
            'min_user' => $validatedData['ReserveFeeMinUser'],
            'max_user' => $validatedData['ReserveFeeMaxUser'],
        ];

        // Buat data baru di database
        ReserveFee::create($reserveFeeData);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Reserve Fee Data Saved',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.reservefee')->with($notification);
    }

    public function UpdateReserveFee(Request $request, $id){
        // Validasi data input
        $validatedData = $request->validate([
            'priceReserveFee' => 'required',
            'ReserveFeeDuration' => 'required|in:1,2,3,4,5,6',
            'ReserveFeeMinUser' => 'required',
            'ReserveFeeMaxUser' => 'required',
        ]);

        // Temukan data berdasarkan ID
        $reserveFeeData = ReserveFee::findOrFail($id);
        $validatedData['priceReserveFee'] = str_replace(',', '', $validatedData['priceReserveFee']);

        // Update data
        $reserveFeeData->update([
            'price' => $validatedData['priceReserveFee'],
            'duration' => $validatedData['ReserveFeeDuration'],
            'min_user' => $validatedData['ReserveFeeMinUser'],
            'max_user' => $validatedData['ReserveFeeMaxUser'],
        ]);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Reserve Fee Data Updated',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.reservefee')->with($notification);
    }

    public function DeleteReserveFee($id){
        try {
            $reserveFee = ReserveFee::findOrFail($id);
            $reserveFee->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data has been successfully deleted',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete data',
            ], 500);
        }
    }
}
