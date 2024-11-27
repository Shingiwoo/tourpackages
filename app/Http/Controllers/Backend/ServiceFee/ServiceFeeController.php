<?php

namespace App\Http\Controllers\Backend\ServiceFee;

use App\Http\Controllers\Controller;
use App\Models\ServiceFee;
use Illuminate\Http\Request;

class ServiceFeeController extends Controller
{
    public function AllService()
    {

        $sFee = ServiceFee::first()->get();

        return view('admin.service.service_fee', compact('sFee'));
    }

    public function StoreServiceFee(Request $request){
        // Validasi data input
        $validatedData = $request->validate([
            'ServiceDuration' => 'required',
            'ServiceMark' => 'required',
        ]);

        // Mapping nama input form ke nama kolom database
        $serviceData = [
            'duration' => $validatedData['ServiceDuration'],
            'mark' => $validatedData['ServiceMark'],
        ];

        // Buat data baru di database
        ServiceFee::create($serviceData);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Service Fee Data Saved',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.service')->with($notification);
    }

    public function UpdateServiceFee(Request $request, $id){
        // Validasi data input
        $validatedData = $request->validate([
            'ServiceDuration' => 'required',
            'ServiceMark' => 'required',
        ]);

        // Temukan data berdasarkan ID
        $serviceFee = ServiceFee::findOrFail($id);

        // Update data
        $serviceFee->update([
            'duration' => $validatedData['ServiceDuration'],
            'mark' => $validatedData['ServiceMark'],
        ]);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Service Fee Data Updated',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.service')->with($notification);
    }

    public function DeleteServiceFee($id){
        try {
            $destination = ServiceFee::findOrFail($id);
            $destination->delete();

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
