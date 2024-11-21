<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function AdminDashboard(){
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

    public function AdminLogin(){
        return view('admin.admin_login');
    }

    public function AdminProfile(){

        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.admin_profile_view', compact('profileData'));
    }

    public function AdminProfileStore(Request $request)
    {
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

        $data->update($validatedData); // Update all validated fields

        // Handle Photo Upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = date('YmdHi') . '.' . $file->getClientOriginalName();

            // Use Laravel's storage facade for disk manipulation
            $storage = Storage::disk('public');  // Choose appropriate disk based on your configuration
            $storage->put('profile/' . $filename, file_get_contents($file)); // Store image using put method

            // Update photo field in database with the stored filename
            $data->photo = $filename;
            $data->save();
        }

        $notification = array(
            'message' => 'Admin Profile Update Successfully',
            'alert-type' => 'success'
        );

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
