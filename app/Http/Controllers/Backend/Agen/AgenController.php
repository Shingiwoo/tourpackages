<?php

namespace App\Http\Controllers\Backend\Agen;

use App\Models\User;
use App\Models\AgenFee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AgenController extends Controller
{
    public function Index()
    {
        return view('agen.index');
    }/**
     * Destroy an authenticated session.
     */
    public function AgenLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function AgenLogin()
    {
        return view('admin.agen_login');
    }

    public function AgenProfile()
    {

        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.agen_profile_view', compact('profileData'));
    }

    public function AgenProfileStore(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string',
            'email' => 'required|string|email|unique:users,email,' . Auth::id(),
            'phone' => 'required|string',
            'address' => 'required|string',
            'bank' => 'required|string',
            'norek' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validation for image upload
        ]);

        $id = Auth::user()->id;
        $data = User::find($id);

        // Hapus gambar lama jika ada
        if (!empty($data->photo)) {
            $oldPhotoPath = public_path('storage/profile/' . $data->photo); // Pastikan path sesuai folder penyimpanan

            if (file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath); // Hapus file jika ditemukan
            }
        }

        // Simpan data baru ke database
        $data->update($validatedData); // Update semua field yang divalidasi

        // Handle Photo Upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = date('YmdHi') . '_' . $file->getClientOriginalName();

            // Simpan file baru ke storage
            $file->storeAs('profile', $filename, 'public');

            // Perbarui nama file di database
            $data->photo = $filename;
            $data->save();
        }

        $notification = [
            'message' => 'Agen Profile Update Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function AgenChangePassword(){

        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.agen_change_password', compact('profileData'));
    }

    public function AgenPasswordUpdate(Request $request){

        //Valiadtion
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if (!Hash::check($request->old_password, Auth::user()->password)) {

            $notification = array(
                'message' => 'Old Password Does not Match!',
                'alert-type' => 'error'
            );

            return back()->with($notification);
        }

        // Update The New Password
        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notification = array(
            'message' => 'Password Change Successfully',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }

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
