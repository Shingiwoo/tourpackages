<?php

namespace App\Http\Controllers\Backend\Facility;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Regency;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function AllFacility()
    {
        $facilities = Facility::latest()->get();
        $cities = Regency::latest()->get();
        return view('admin.facility.all_facility', compact('facilities', 'cities'));
    }

    public function StoreFacility(Request $request){
        // Validasi data input
        $validatedData = $request->validate([
            'nameFacility' => 'required',
            'cityOrDistrict_id' => 'required|exists:regencies,id',
            'priceFacility' => 'required',
            'typeFacility' => 'required|in:flat,per_person,per_day,info,shuttle',
            'maxuserFacility' => 'required',
        ]);

        $validatedData['priceFacility'] = str_replace(',', '', $validatedData['priceFacility']);

        // Mapping nama input form ke nama kolom database
        $facilityData = [
            'name' => $validatedData['nameFacility'],
            'price' => $validatedData['priceFacility'],
            'type' => $validatedData['typeFacility'],
            'max_user' => $validatedData['maxuserFacility'],
            'regency_id' => $validatedData['cityOrDistrict_id'],
        ];

        // Buat data baru di database
        Facility::create($facilityData);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Facility Data Saved',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.facility')->with($notification);
    }

    public function EditFacility($id)
    {
        $facility = Facility::findOrFail($id);
        $regencies = Regency::latest()->get();
        return view('admin.facility.edit_facility', compact('facility', 'regencies'));
    }

    public function UpdateFacility(Request $request){

        $facilityId = $request->id;

        // Temukan data vehicle berdasarkan ID
        $facility = Facility::findOrFail($facilityId);

        // Validasi data input
        $validatedData = $request->validate([
            'nameFacility' => 'required',
            'cityOrDistrict_id' => 'required|exists:regencies,id',
            'priceFacility' => 'required',
            'typeFacility' => 'required|in:flat,per_person,per_day,info,shuttle',
            'maxuserFacility' => 'required',
        ]);

        $validatedData['priceFacility'] = str_replace(',', '', $validatedData['priceFacility']);

        // Update data
        $validatedData = [
            'name' => $validatedData['nameFacility'],
            'price' => $validatedData['priceFacility'],
            'type' => $validatedData['typeFacility'],
            'max_user' => $validatedData['maxuserFacility'],
            'regency_id' => $validatedData['cityOrDistrict_id'],
        ];

        // Update data di database
        $facility->update($validatedData);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Facility Data Updated',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.facility')->with($notification);
    }

    public function DeleteFacility($id){
        try {
            $facilityData = Facility::findOrFail($id);
            $facilityData->delete();

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
