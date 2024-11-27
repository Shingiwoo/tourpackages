<?php

namespace App\Http\Controllers\Backend\OtherService;

use App\Http\Controllers\Controller;
use App\Models\AgenFee;
use App\Models\Crew;
use App\Models\Facility;
use App\Models\Meal;
use App\Models\ServiceFee;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\SeriesSum;

class OtherServiceController extends Controller
{
    public function AllService()
    {

        $agenFee = AgenFee::find(1);
        $crew = Crew::latest()->get();
        $facility = Facility::latest()->get();
        $meal = Meal::latest()->get();
        $sFee = ServiceFee::first()->get();

        return view('admin.service.service_fee', compact('agenFee', 'crew', 'facility', 'meal', 'sFee'));
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
