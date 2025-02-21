<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\User;
use App\Models\Booking;
use App\Models\BookingList;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        $bookedStatus = Booking::where('status', 'booked')->count();

        $paidStatus = Booking::where('status', 'paid')->count();

        $pendingStatus = Booking::where('status', 'pending')->count();

        $bookedTotal = $bookedStatus + $paidStatus;

        $finishedStatus = Booking::where('status', 'finished')->count();

        $agenRanking = $this->getAgenRanking();

        return view('admin.index', compact('bookedTotal', 'finishedStatus', 'pendingStatus', 'agenRanking'));
    }

    public function getAgenRanking()
    {
        $agenRanking = BookingList::with('agen')
            ->withCount([
                'bookings as total_booked' => function ($query) {
                    $query->where('status', 'booked');
                },
                'bookings as total_paid' => function ($query) {
                    $query->where('status', 'paid');
                },
                'bookings as total_finished' => function ($query) {
                    $query->where('status', 'finished');
                }
            ])
            ->get()
            ->groupBy('agen_id') // Kelompokkan berdasarkan agen_id
            ->map(function ($bookings, $agenId) {
                return [
                    'agen_id' => $agenId,
                    'agen_name' => $bookings->first()->agen->username ?? 'Unknown',
                    'agen_company' => $bookings->first()->agen->company ?? 'Tour Package',
                    'total_tour' => $bookings->sum('total_booked') +
                                    $bookings->sum('total_paid') +
                                    $bookings->sum('total_finished'),
                ];
            })
            ->sortByDesc('total_tour')
            ->values();

        return $agenRanking;
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

    public function AllAdmin()
    {
        $alladmin = User::where('role', 'admin')->get();
        return view('admin.access.all_admin', compact('alladmin'));
    }

    public function AddAdmin()
    {
        $roles = Role::all();
        return view('admin.access.add_admin', compact('roles'));
    }

    public function StoreAdmin(Request $request)
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
        $role = $roleID == '12' ? 'agen' : 'admin';

        // Buat data untuk user
        $adminData = [
            'name' => $validatedData['fullname'],
            'username' => $validatedData['alias'],
            'email' => $validatedData['youremail'],
            'phone' => $validatedData['yourphone'],
            'role' => $role, // Kolom 'role' berisi 'admin' atau 'agen'
            'password' => Hash::make($validatedData['password']),
            'address' => $validatedData['fulladdress'],
        ];

        // Simpan user ke database
        $user = User::create($adminData);

        // Assign role menggunakan nama role
        $user->assignRole($roleName);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Admin Data Created successfully!',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.admin')->with($notification);
    }

    public function EditAdmin($id)
    {
        $user = User::findOrfail($id);
        $roles = Role::all();
        return view('admin.access.edit_admin', compact('roles','user'));
    }

    public function UpdateAdmin(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'fullname' => 'required|string|max:255',
                'alias' => 'required|string|unique:users,username,' . $id, // Perbaiki validasi unique
                'youremail' => 'required|string|email|unique:users,email,' . $id, // Kecualikan email user ini
                'yourphone' => 'required|string',
                'adminStatus' => 'required|in:active,inactive',
                'fulladdress' => 'required|string',
                'roleID' => 'required|exists:roles,id', // Pastikan roleID valid
            ]);

            // Log::info('Validated Data:', $validatedData);

            // Simpan nilai asli roleID
            $roleID = $validatedData['roleID'];

            // Ambil nama role berdasarkan roleID
            $roleName = Role::findOrFail($roleID)->name;

            // Tentukan nilai role untuk disimpan di kolom 'role'
            $role = $roleID == '12' ? 'agen' : 'admin';

            // Buat data untuk user
            $adminData = [
                'name' => $validatedData['fullname'],
                'username' => $validatedData['alias'],
                'email' => $validatedData['youremail'],
                'phone' => $validatedData['yourphone'],
                'status' => $validatedData['adminStatus'],
                'role' => $role, // Kolom 'role' berisi 'admin' atau 'agen'
                'address' => $validatedData['fulladdress'],
            ];

            // Cari user berdasarkan ID
            $user = User::findOrFail($id);

            // Update data user
            $user->update($adminData);

            // Hapus role lama
            $user->roles()->detach();

            // Assign role baru menggunakan nama role
            $user->assignRole($roleName);

            // Kirim notifikasi berhasil
            $notification = [
                'message' => 'Admin Data Updated successfully!',
                'alert-type' => 'success',
            ];

            return redirect()->route('all.admin')->with($notification);

        } catch (\Exception $e) {
            Log::error('Error in UpdateAdmin:', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors('An error occurred while updating admin data.');
        }
    }

    public function DeleteAdmin($id)
    {
        try {
            $adminData = User::findOrFail($id);

            $adminData->roles()->detach();
            $adminData->delete();

            return response()->json([
                'success' => true,
                'message' => 'Admin Data has been successfully deleted',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Admin data',
            ], 500);
        }
    }


}
