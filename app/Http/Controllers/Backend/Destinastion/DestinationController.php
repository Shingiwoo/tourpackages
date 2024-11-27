<?php

namespace App\Http\Controllers\Backend\Destinastion;

use App\Models\Regency;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Imports\DestinationsImport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class DestinationController extends Controller
{
    public function AllDestinations()
    {

        $destins = Destination::latest()->get();
        $active = Destination::where('status', 1)->count();
        $inactive = Destination::where('status', 0)->count();
        $person = Destination::where('price_type', 'per_person')->count();
        $flat = Destination::where('price_type', 'flat')->count();
        return view('admin.destination.index', compact('destins', 'active', 'inactive', 'person', 'flat'));
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

        $validatedData['priceWni'] = str_replace(',', '', $validatedData['priceWni']);
        $validatedData['priceWna'] = str_replace(',', '', $validatedData['priceWna']);

        $validatedData['carParkingFees'] = str_replace(',', '', $validatedData['carParkingFees']);
        $validatedData['minibusParkingFees'] = str_replace(',', '', $validatedData['minibusParkingFees']);
        $validatedData['busParkingFees'] = str_replace(',', '', $validatedData['busParkingFees']);

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



        $validatedData['priceWni'] = str_replace(',', '', $validatedData['priceWni']);
        $validatedData['priceWna'] = str_replace(',', '', $validatedData['priceWna']);
        $validatedData['carParkingFees'] = str_replace(',', '', $validatedData['carParkingFees']);
        $validatedData['minibusParkingFees'] = str_replace(',', '', $validatedData['minibusParkingFees']);
        $validatedData['busParkingFees'] = str_replace(',', '', $validatedData['busParkingFees']);

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


    public function DeleteDestination($id){
        try {
            $destination = Destination::findOrFail($id);
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



    public function PageImportDestinations(){

        return view('admin.destination.import_destinations');
    }

    public function ImportDestination(Request $request){
        try {
            if (!$request->hasFile('file')) {
                return redirect()->back()->with([
                    'message' => 'No file uploaded. Please upload a valid CSV or Excel file.',
                    'alert-type' => 'error',
                ]);
            }

            $file = $request->file('file');

            Log::info('File uploaded successfully: ' . $file->getClientOriginalName());

            // Validasi tipe file
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:csv,xlsx',
            ]);

            if ($validator->fails()) {
                Log::warning('File validation failed.');
                return redirect()->back()->with([
                    'message' => 'Invalid file format. Only CSV or Excel files are allowed.',
                    'alert-type' => 'error',
                ]);
            }

            Log::info('File validation passed. Starting Excel import.');

            // Proses file
            Excel::import(new DestinationsImport, $file);

            Log::info('Excel import process completed.');

            return redirect()->back()->with([
                'message' => 'Data imported successfully.',
                'alert-type' => 'success',
            ]);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            Log::error('Validation errors in Excel file: ' . json_encode($e->failures()));
            $failures = $e->failures();
            $errorMessages = '';
            foreach ($failures as $failure) {
                $errorMessages .= "Row {$failure->row()}: " . implode(', ', $failure->errors()) . "\n";
            }

            return redirect()->back()->with([
                'message' => "Import failed:\n$errorMessages",
                'alert-type' => 'error',
            ]);
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => 'An error occurred during import: ' . $e->getMessage(),
                'alert-type' => 'error',
            ]);
        }
    }



}
