<?php

namespace App\Http\Controllers\Backend\Role;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function AllPermission()
    {
        $permissions = Permission::latest()->get();

        return view('admin.role.all_permission', compact('permissions'));
    }

    public function StorePermission(Request $request)
    {
        Log::info('Request Data:', $request->all());

        // Validasi data input
        $validatedData = $request->validate([
            'permissionName' => 'required|string|max:255',
            'permissionGroup' => 'required|string|max:255',
        ]);

        Log::info('Check Data Validasi:', $validatedData);

        // Mapping nama input form ke nama kolom database
        $permissionData = [
            'name' => $validatedData['permissionName'],
            'group_name' => $validatedData['permissionGroup'],
        ];

        // Buat data baru di database
        Permission::create($permissionData);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Permission Data Saved',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.permission')->with($notification);
    }


    public function EditPermission(Request $request, $id)
    {
        $permissions = Permission::all();

        return view('admin.role.edit_permission', compact('permissions'));
    }

    public function UpdatePermission(Request $request, $id)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'permissionName' => 'required',
            'permissionGroup' => 'required|string',
        ]);

        // Temukan data berdasarkan ID
        $permissionData = Permission::findOrFail($id);

        // Update data
        $permissionData->update([
            'name' => $validatedData['permissionName'],
            'group_name' => $validatedData['permissionGroup'],
        ]);

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Permission Data Updated',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.permission')->with($notification);
    }

    public function DeletePermission($id)
    {
        try {
            $permissionData = Permission::findOrFail($id);
            $permissionData->delete();

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
