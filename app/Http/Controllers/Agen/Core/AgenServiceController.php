<?php

namespace App\Http\Controllers\Agen\Core;

use App\Models\User;
use App\Models\Custom;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\PackageOneDay;
use App\Models\PackageTwoDay;
use App\Models\PackageFourDay;
use App\Models\PackageThreeDay;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AgenServiceController extends Controller
{
    public function AgenLogin()
    {
        return view('agen.agen_login');
    }

    public function AgenDashboard()
    {
        $agen = Auth::user();

        $totalPackage = PackageOneDay::countByAgen($agen->id)
            + PackageTwoDay::countByAgen($agen->id)
            + PackageThreeDay::countByAgen($agen->id)
            + PackageFourDay::countByAgen($agen->id);
            +Custom::whereRaw("JSON_EXTRACT(custompackage, '$.agen_id') = ?", [$agen->id])->count();


        $pendingStatus = Booking::whereHas('bookingList', function ($query) use ($agen) {
            $query->where('agen_id', $agen->id);
        })->where('status', 'pending')->count();

        $bookedStatus = Booking::whereHas('bookingList', function ($query) use ($agen) {
            $query->where('agen_id', $agen->id);
        })->where('status', 'booked')->count();

        $finishedStatus = Booking::whereHas('bookingList', function ($query) use ($agen) {
            $query->where('agen_id', $agen->id);
        })->where('status', 'finished')->count();


        return view('agen.index', compact('agen', 'totalPackage', 'pendingStatus', 'bookedStatus', 'finishedStatus'));
    }

    public function AgenLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function AgenProfile()
    {

        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('agen.agen_profile_view', compact('profileData'));
    }

    public function AgenProfileStore(Request $request)
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

    public function AgenChangePassword()
    {

        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('agen.agen_change_password', compact('profileData'));
    }

    public function AgenPasswordUpdate(Request $request)
    {

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
