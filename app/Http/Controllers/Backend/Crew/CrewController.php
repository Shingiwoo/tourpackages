<?php

namespace App\Http\Controllers\Backend\Crew;

use App\Models\Crew;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CrewController extends Controller
{
    public function AllCrew()
    {
        $Crew = Crew::latest()->get();
        return view('admin.crew.all_crew', compact('Crew'));
    }

    public function StoreCrew(Request $request){
        // Validasi data input
        $validatedData = $request->validate([
            'minUser' => 'required',
            'maxUser' => 'required',
            'totalCrew' => 'required',
        ]);

        // Mapping nama input form ke nama kolom database
        $dataCrew = [
            'min_participants' => $validatedData['minUser'],
            'max_participants' => $validatedData['maxUser'],
            'num_crew' => $validatedData['totalCrew'],
        ];

        // Buat data baru di database
        Crew::create($dataCrew);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Crew Data Saved',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.crew')->with($notification);
    }

    public function UpdateCrew(Request $request, $id){
        // Validasi data input
        $validatedData = $request->validate([
            'minUser' => 'required',
            'maxUser' => 'required',
            'totalCrew' => 'required',
        ]);

        // Temukan data berdasarkan ID
        $dataCrew = Crew::findOrFail($id);

        // Update data
        $dataCrew->update([
            'min_participants' => $validatedData['minUser'],
            'max_participants' => $validatedData['maxUser'],
            'num_crew' => $validatedData['totalCrew'],
        ]);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Crew Data Updated',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.crew')->with($notification);
    }

    public function DeleteCrew($id){
        try {
            $dataCrew = Crew::findOrFail($id);
            $dataCrew->delete();

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
