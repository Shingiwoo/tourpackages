<?php

namespace App\Http\Controllers\Backend\Vehicle;

use App\Models\Regency;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Imports\VehiclesImport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    public function AllVehicles()
    {

        $vehicles = Vehicle::latest()->get();
        $statAct = Vehicle::where('status', 1)->count();
        $statInact = Vehicle::where('status', 0)->count();
        $cityCar = Vehicle::where('type', 'City Car')->count();
        $miniBus = Vehicle::where('type', 'Mini Bus')->count();
        $bus = Vehicle::where('type', 'Bus')->count();
        return view('admin.vehicles.index', compact('vehicles', 'cityCar', 'miniBus', 'bus', 'statAct', 'statInact'));
    }

    public function AddVehicle()
    {
        $regencies = Regency::latest()->get();
        return view('admin.vehicles.add_vehicle', compact( 'regencies'));
    }

    public function StoreVehicle(Request $request){
        // Validasi data input
        $validatedData = $request->validate([
            'cityOrDistrict_id' => 'required|exists:regencies,id', // Validasi relasi dengan regencies
            'NameVehicle' => 'required|string|max:255',
            'statusVehicle' => 'required|boolean',
            'carType' => 'required|in:City Car,Mini Bus,Bus',
            'carPrice' => 'required|string',
            'carCapacity_min' => 'required|string', // Validasi untuk price type
            'carCapacity_max' => 'required|string',
        ]);

        // Format harga: hapus koma sebelum disimpan
        $validatedData['carPrice'] = str_replace(',', '', $validatedData['carPrice']);

        // Pastikan kapasitas minimal tidak lebih besar dari kapasitas maksimal
        if ((int)$validatedData['carCapacity_min'] > (int)$validatedData['carCapacity_max']) {
            return redirect()->back()->withErrors(['carCapacity_min' => 'Minimum capacity cannot exceed maximum capacity.'])->withInput();
        }


        // Mapping nama input form ke nama kolom database
        $vehicleData = [
            'regency_id' => $validatedData['cityOrDistrict_id'],
            'name' => $validatedData['NameVehicle'],
            'status' => $validatedData['statusVehicle'],
            'type' => $validatedData['carType'],
            'price' => $validatedData['carPrice'],
            'capacity_min' => $validatedData['carCapacity_min'],
            'capacity_max' => $validatedData['carCapacity_max'],
        ];

        // Buat data baru di database
        Vehicle::create($vehicleData);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Vehicle Data Saved',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.vehicles')->with($notification);
    }



    public function EditVehicle($id){

        $vehicle = Vehicle::findOrFail($id);
        $regencies = Regency::latest()->get();
        return view('admin.vehicles.edit_vehicle', compact('vehicle', 'regencies'));
    }




    public function UpdateVehicle(Request $request){
        $vehicleId = $request->id;

        // Temukan data vehicle berdasarkan ID
        $vehicle = Vehicle::findOrFail($vehicleId);

        // Validasi data input
        $validatedData = $request->validate([
            'cityOrDistrict_id' => 'required|exists:regencies,id', // Validasi relasi dengan regencies
            'NameVehicle' => 'required|string|max:255',
            'statusVehicle' => 'required|boolean',
            'carType' => 'required|in:City Car,Mini Bus,Bus', // Validasi tipe mobil
            'carPrice' => 'required|string', // Harga wajib diisi
            'carCapacity_min' => 'required|integer|min:1', // Kapasitas minimal harus angka
            'carCapacity_max' => 'required|integer|min:1', // Kapasitas maksimal harus angka
        ]);

        // Format harga: hapus koma sebelum disimpan
        $validatedData['carPrice'] = str_replace(',', '', $validatedData['carPrice']);

        // Pastikan kapasitas minimal tidak lebih besar dari kapasitas maksimal
        if ((int)$validatedData['carCapacity_min'] > (int)$validatedData['carCapacity_max']) {
            return redirect()->back()->withErrors(['carCapacity_min' => 'Minimum capacity cannot exceed maximum capacity.'])->withInput();
        }

        // Mapping nama input form ke nama kolom database
        $vehicleData = [
            'regency_id' => $validatedData['cityOrDistrict_id'],
            'name' => $validatedData['NameVehicle'],
            'status' => $validatedData['statusVehicle'],
            'type' => $validatedData['carType'],
            'price' => $validatedData['carPrice'], // Harga yang sudah diformat
            'capacity_min' => $validatedData['carCapacity_min'],
            'capacity_max' => $validatedData['carCapacity_max'],
        ];

        // Update data di database
        $vehicle->update($vehicleData);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Vehicle Data Updated Successfully!',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman kendaraan dengan notifikasi
        return redirect()->route('all.vehicles')->with($notification);
    }



    public function DeleteVehicle($id){
        try {
            $vehicle = Vehicle::findOrFail($id);
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



    public function PageImportVehicles(){

        return view('admin.vehicles.import_vehicles');
    }

    public function ImportVehicles(Request $request){
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
            Excel::import(new VehiclesImport, $file);

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
