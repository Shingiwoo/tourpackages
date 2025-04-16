<?php

namespace App\Http\Controllers\Backend\Hotel;

use App\Models\Hotel;
use App\Models\Regency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class HotelController extends Controller
{
    public function AllHotels()
    {

        $hotels = Hotel::latest()->get();
        $active = Hotel::where('status', 1)->count();
        $inactive = Hotel::where('status', 0)->count();
        $regencies = Regency::latest()->get();
        return view('admin.hotel.index', compact('hotels', 'active', 'inactive', 'regencies'));
    }

    public function AddHotel()
    {
        $regencies = Regency::latest()->get();
        return view('admin.hotel.add_hotel', compact( 'regencies'));
    }


    public function StoreHotel(Request $request){
        try {
            Log::info('StoreHotel method initiated.');

            // Validasi data input
            $validatedData = $request->validate([
                'cityOrDistrict_id' => 'required|exists:regencies,id',
                'NameHotel' => 'required|string|max:255',
                'hotelCapacity' => 'required|string',
                'statusHotel' => 'required|boolean',
                'hotelType' => 'required|in:TwoStar,ThreeStar,FourStar,FiveStar,Villa,Homestay,Cottage,Cabin,Guesthouse,WithoutAccomodation',
                'hotelPrice' => 'required|string',
                'hotelExtrabedPrice' => 'required|string',
            ]);
            Log::info('Validation passed.', ['validated_data' => $validatedData]);

            // Format harga: hapus koma sebelum disimpan
            $validatedData['hotelPrice'] = str_replace(',', '', $validatedData['hotelPrice']);
            $validatedData['hotelExtrabedPrice'] = str_replace(',', '', $validatedData['hotelExtrabedPrice']);
            Log::info('Formatted prices.', [
                'hotelPrice' => $validatedData['hotelPrice'],
                'hotelExtrabedPrice' => $validatedData['hotelExtrabedPrice']
            ]);

            // Mapping nama input form ke nama kolom database
            $hotelData = [
                'regency_id' => $validatedData['cityOrDistrict_id'],
                'name' => $validatedData['NameHotel'],
                'type' => $validatedData['hotelType'],
                'capacity' => $validatedData['hotelCapacity'],
                'price' => $validatedData['hotelPrice'],
                'extrabed_price' => $validatedData['hotelExtrabedPrice'],
                'status' => $validatedData['statusHotel'],
            ];
            Log::info('Mapped hotel data.', ['hotel_data' => $hotelData]);

            // Buat data baru di database
            $hotel = Hotel::create($hotelData);
            Log::info('Hotel data saved successfully.', ['hotel_id' => $hotel->id]);

            // Kirim notifikasi berhasil
            $notification = [
                'message' => 'Hotel Data Saved',
                'alert-type' => 'success',
            ];
            Log::info('Notification prepared.', ['notification' => $notification]);

            // Redirect ke halaman destinasi dengan notifikasi
            return redirect()->route('all.hotels')->with($notification);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log error validasi
            Log::error('Validation error in StoreHotel.', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Log error umum
            Log::error('Unexpected error in StoreHotel.', ['message' => $e->getMessage()]);
            return redirect()->back()->with([
                'message' => 'An error occurred while saving hotel data.',
                'alert-type' => 'error'
            ])->withInput();
        }
    }

    public function EditHotel($id){

        $hotel = Hotel::findOrFail($id);
        $regencies = Regency::latest()->get();
        return view('admin.hotel.edit_hotel', compact('hotel', 'regencies'));
    }

    public function UpdateHotel(Request $request, $id){
        // Temukan data hotel berdasarkan ID
        $hotel = Hotel::findOrFail($id);

        // Validasi data input
        $validatedData = $request->validate([
            'cityOrDistrict_id' => 'required|exists:regencies,id', // Validasi relasi dengan regencies
            'NameHotel' => 'required|string|max:255',
            'hotelCapacity' => 'required|string',
            'statusHotel' => 'required|boolean',
            'hotelType' => 'required|in:TwoStar,ThreeStar,FourStar,FiveStar,Villa,Homestay,Cottage,Cabin,Guesthouse,WithoutAccomodation',
            'hotelPrice' => 'required|string',
            'hotelExtrabedPrice' => 'required|string',
        ]);

        // Format harga: hapus koma sebelum disimpan
        $validatedData['hotelPrice'] = str_replace(',', '', $validatedData['hotelPrice']);
        $validatedData['hotelExtrabedPrice'] = str_replace(',', '', $validatedData['hotelExtrabedPrice']);

        // Mapping nama input form ke nama kolom database
        $hotelData = [
            'regency_id' => $validatedData['cityOrDistrict_id'],
            'name' => $validatedData['NameHotel'],
            'status' => $validatedData['statusHotel'],
            'type' => $validatedData['hotelType'],
            'capacity' => $validatedData['hotelCapacity'],
            'price' => $validatedData['hotelPrice'],
            'extrabed_price' => $validatedData['hotelExtrabedPrice'],
        ];

        // Buat data baru di database
        $hotel->update($hotelData);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Hotel Data Updated!',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.hotels')->with($notification);
    }



    public function DeleteHotel($id){
        try {
            $vehicle = Hotel::findOrFail($id);
            $vehicle->delete();

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
