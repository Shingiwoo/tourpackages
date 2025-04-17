<?php

namespace App\Http\Controllers\Backend\Agen;

use App\Models\User;
use App\Models\AgenFee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AgenController extends Controller
{
    public function AllAgenFee()
    {
        $agenFee = AgenFee::find(1);
        return view('admin.agen.all_agen_fee', compact('agenFee'));
    }

    public function UpdateAgenFee(Request $request){

        $agenFeeID = $request->id;

        // Validasi data input
        $validatedData = $request->validate([
            'agenFee' => 'required',
        ]);

        // Format harga: hapus koma sebelum disimpan
        $validatedData['agenFee'] = str_replace(',', '', $validatedData['agenFee']);

        // Temukan data berdasarkan ID
        $dataAgenFee = AgenFee::findOrFail($agenFeeID);

        // Update data
        $dataAgenFee->update([
            'price' => $validatedData['agenFee'],
        ]);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Agen Fee Data Updated',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.agen.fee')->with($notification);
    }

    public function AllAgen()
    {
        $allagens = User::where('role', 'agen')->get();
        $countAgen = User::where('role','agen')->count();
        return view('admin.agen.index', compact('allagens', 'countAgen'));
    }

    public function AddAgen()
    {
        $roles = Role::where('name', 'Agen')->get();
        return view('admin.agen.add_agen', compact('roles'));
    }

    public function StoreAgen(Request $request)
    {
        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'alias' => 'required|string|unique:users,username',
            'youremail' => 'required|string|email|unique:users,email',
            'yourphone' => 'required|string',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'fulladdress' => 'required|string',
            'roleID' => 'required|exists:roles,id', // Pastikan roleID valid
        ]);

        // Simpan nilai asli roleID
        $roleID = $validatedData['roleID'];

        // Ambil nama role berdasarkan roleID
        $roleName = Role::findOrFail($roleID)->name;

        // Tentukan nilai role untuk disimpan di kolom 'role'
        $role = 'agen';

        // Buat data untuk user
        $agenData = [
            'name' => $validatedData['fullname'],
            'username' => $validatedData['alias'],
            'email' => $validatedData['youremail'],
            'phone' => $validatedData['yourphone'],
            'role' => $role, // Kolom 'role' berisi 'agen' atau 'agen'
            'password' => Hash::make($validatedData['password']),
            'address' => $validatedData['fulladdress'],
        ];

        // Simpan user ke database
        $user = User::create($agenData);

        // Assign role menggunakan nama role
        $user->assignRole($roleName);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Agen Data Created successfully!',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.agen')->with($notification);
    }

    public function EditAgen($id)
    {
        $user = User::findOrfail($id);
        $roles = Role::where('name', 'Agen')->get();
        return view('admin.agen.edit_agen', compact('roles','user'));
    }

    public function UpdateAgen(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'fullname' => 'required|string|max:255',
                'alias' => 'required|string|unique:users,username,' . $id, // Perbaiki validasi unique
                'youremail' => 'required|string|email|unique:users,email,' . $id, // Kecualikan email user ini
                'yourphone' => 'required|string',
                'agenStatus' => 'required|in:active,inactive',
                'fulladdress' => 'required|string',
                'roleID' => 'required|exists:roles,id', // Pastikan roleID valid
            ]);

            // Log::info('Validated Data:', $validatedData);

            // Simpan nilai asli roleID
            $roleID = $validatedData['roleID'];

            // Ambil nama role berdasarkan roleID
            $roleName = Role::findOrFail($roleID)->name;

            // Tentukan nilai role untuk disimpan di kolom 'role'
            $role = 'agen';

            // Buat data untuk user
            $agenData = [
                'name' => $validatedData['fullname'],
                'username' => $validatedData['alias'],
                'email' => $validatedData['youremail'],
                'phone' => $validatedData['yourphone'],
                'status' => $validatedData['agenStatus'],
                'role' => $role, // Kolom 'role' berisi 'agen' atau 'agen'
                'address' => $validatedData['fulladdress'],
            ];

            // Cari user berdasarkan ID
            $user = User::findOrFail($id);

            // Update data user
            $user->update($agenData);

            // Hapus role lama
            $user->roles()->detach();

            // Assign role baru menggunakan nama role
            $user->assignRole($roleName);

            // Kirim notifikasi berhasil
            $notification = [
                'message' => 'Agen Data Updated successfully!',
                'alert-type' => 'success',
            ];

            return redirect()->route('all.agen')->with($notification);

        } catch (\Exception $e) {
            Log::error('Error in UpdateAgen:', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors('An error occurred while updating agen data.');
        }
    }

    public function DeleteAgen($id)
    {
        try {
            $agenData = User::findOrFail($id);

            $agenData->roles()->detach();
            $agenData->delete();

            return response()->json([
                'success' => true,
                'message' => 'Agen Data has been successfully deleted',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Agen data',
            ], 500);
        }
    }
}
