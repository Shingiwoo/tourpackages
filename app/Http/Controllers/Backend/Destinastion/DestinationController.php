<?php

namespace App\Http\Controllers\Backend\Destinastion;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Regency;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function AllDestinations()
    {

        $destins = Destination::latest()->get();
        return view('admin.destination.index', compact('destins'));
    }

    public function AddDestination()
    {
        $regencies = Regency::latest()->get();
        return view('admin.destination.add_destination', compact( 'regencies'));
    }

    public function StoreDestination(Request $request){
        // Validasi data input
        $validatedData = $request->validate([
            'cityOrDistrict_id' => 'required|exists:regencies,id', // Validasi relasi dengan regencies
            'NameDestination' => 'required|string|max:255',
            'statusDestination' => 'required|boolean',
            'carParkingFees' => 'nullable|string',
            'minibusParkingFees' => 'nullable|string',
            'busParkingFees' => 'nullable|string',
            'information' => 'nullable|string',
            'priceWni' => 'nullable|string',
            'priceWna' => 'nullable|string',
            'priceType' => 'required|in:flat,per_person', // Validasi untuk price type
            'maxUser' => 'nullable|integer|min:1',
        ]);

        // Mapping nama input form ke nama kolom database
        $destinationData = [
            'regency_id' => $validatedData['cityOrDistrict_id'],
            'name' => $validatedData['NameDestination'],
            'status' => $validatedData['statusDestination'],
            'parking_city_car' => $validatedData['carParkingFees'],
            'parking_mini_bus' => $validatedData['minibusParkingFees'],
            'parking_bus' => $validatedData['busParkingFees'],
            'ket' => $validatedData['information'],
            'price_wni' => $validatedData['priceWni'],
            'price_wna' => $validatedData['priceWna'],
            'price_type' => $validatedData['priceType'],
            'max_participants' => $validatedData['maxUser'],
        ];

        // Buat data baru di database
        Destination::create($destinationData);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Destination Data Saved',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.destinations')->with($notification);
    }

    public function EditDestination($id){

        $dest = Destination::findOrFail($id);
        $regencies = Regency::latest()->get();
        return view('admin.destination.edit_destination', compact('dest', 'regencies'));
    }




    public function UpdateDestination(Request $request){
        $destinationId = $request->id;

        // Temukan data destination berdasarkan ID
        $destination = Destination::findOrFail($destinationId);

        // Validasi data input
        $validatedData = $request->validate([
            'cityOrDistrict_id' => 'required|exists:regencies,id', // Validasi relasi dengan regencies
            'NameDestination' => 'required|string|max:255',
            'statusDestination' => 'required|boolean',
            'carParkingFees' => 'nullable|string',
            'minibusParkingFees' => 'nullable|string',
            'busParkingFees' => 'nullable|string',
            'information' => 'nullable|string',
            'priceWni' => 'nullable|string',
            'priceWna' => 'nullable|string',
            'priceType' => 'required|in:flat,per_person', // Validasi untuk price type
            'maxUser' => 'nullable|integer|min:1',
        ]);

        // Mapping nama input form ke nama kolom database
        $destinationData = [
            'regency_id' => $validatedData['cityOrDistrict_id'],
            'name' => $validatedData['NameDestination'],
            'status' => $validatedData['statusDestination'],
            'parking_city_car' => $validatedData['carParkingFees'],
            'parking_mini_bus' => $validatedData['minibusParkingFees'],
            'parking_bus' => $validatedData['busParkingFees'],
            'ket' => $validatedData['information'],
            'price_wni' => $validatedData['priceWni'],
            'price_wna' => $validatedData['priceWna'],
            'price_type' => $validatedData['priceType'],
            'max_participants' => $validatedData['maxUser'],
        ];

        // Buat data baru di database
        $destination->update($destinationData);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Destination Data Updated',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.destinations')->with($notification);
    }
}
