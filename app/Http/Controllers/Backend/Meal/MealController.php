<?php

namespace App\Http\Controllers\Backend\Meal;

use App\Models\Meal;
use App\Models\Regency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MealController extends Controller
{
    public function AllMeal()
    {

        $meals = Meal::all();
        $regencies = Regency::all();

        return view('admin.meal.all_meal', compact('meals','regencies'));
    }

    public function StoreMeal(Request $request){
        // Validasi data input
        $validatedData = $request->validate([
            'priceMeal' => 'required',
            'mealType' => 'required|in:1D,2D,3D,4D,5D,Honeymoon,Custom',
            'mealDuration' => 'required|in:1,2,3,4,5,6',
            'totalMeal' => 'required|string',
            'cityOrDistrict_id' => 'required|exists:regencies,id',
        ]);

        $validatedData['priceMeal'] = str_replace(',', '', $validatedData['priceMeal']);

        // Mapping nama input form ke nama kolom database
        $serviceData = [
            'price' => $validatedData['priceMeal'],
            'type' => $validatedData['mealType'],
            'duration' => $validatedData['mealDuration'],
            'num_meals' => $validatedData['totalMeal'],
            'regency_id' => $validatedData['cityOrDistrict_id'],
        ];

        // Buat data baru di database
        Meal::create($serviceData);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Meal Data Saved',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.meals')->with($notification);
    }

    public function UpdateMeal(Request $request, $id){
        // Validasi data input
        $validatedData = $request->validate([
            'priceMeal' => 'required',
            'mealType' => 'required|in:1D,2D,3D,4D,5D',
            'mealDuration' => 'required|in:1,2,3,4,5',
            'totalMeal' => 'required|string',
            'cityOrDistrict_id' => 'required|exists:regencies,id',
        ]);

        // Temukan data berdasarkan ID
        $serviceMeal = Meal::findOrFail($id);
        $validatedData['priceMeal'] = str_replace(',', '', $validatedData['priceMeal']);

        // Update data
        $serviceMeal->update([
            'price' => $validatedData['priceMeal'],
            'type' => $validatedData['mealType'],
            'duration' => $validatedData['mealDuration'],
            'num_meals' => $validatedData['totalMeal'],
            'regency_id' => $validatedData['cityOrDistrict_id'],
        ]);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Meal Data Updated',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.meals')->with($notification);
    }

    public function DeleteMeal($id){
        try {
            $service = Meal::findOrFail($id);
            $service->delete();

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
