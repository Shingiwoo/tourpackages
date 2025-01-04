<?php

namespace App\Http\Controllers\Backend\Role;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function AllRoles()
    {
        $roles = Role::latest()->get();

        return view('admin.role.all_role', compact('roles'));
    }

    public function StoreRole(Request $request)
    {
        Log::info('Request Data:', $request->all());

        // Validasi data input
        $validatedData = $request->validate([
            'RoleName' => 'required|string|max:255',
        ]);

        Log::info('Check Data Validasi:', $validatedData);

        // Mapping nama input form ke nama kolom database
        $roleData = [
            'name' => $validatedData['RoleName'],
        ];

        // Buat data baru di database
        Role::create($roleData);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Role Data Saved',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.roles')->with($notification);
    }

    public function EditRole($id)
    {
        $role = Role::findOrFail($id);

        return view('admin.role.edit_role', compact('role'));
    }

    public function UpdateRole(Request $request, $id)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'roleName' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        // Temukan data berdasarkan ID
        $roleData = Role::findOrFail($id);

        // Update data
        $roleData->update([
            'name' => $validatedData['roleName'],
        ]);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Role Data Updated',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.role')->with($notification);
    }

    public function DeleteRole($id)
    {
        try {
            $roleData = Role::findOrFail($id);
            $roleData->delete();

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
