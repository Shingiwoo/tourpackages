<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        return view('admin.index');
    }

    /**
     * Destroy an authenticated session.
     */
    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    public function AdminLogin()
    {
        return view('admin.admin_login');
    }

    public function AdminProfile()
    {

        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.admin_profile_view', compact('profileData'));
    }

    public function AdminProfileStore(Request $request) {
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
            'message' => 'Admin Profile Update Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }




    public function AdminChangePassword(){

        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.admin_change_password', compact('profileData'));
    }

    public function AdminPasswordUpdate(Request $request){

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
}
