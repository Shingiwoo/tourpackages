<?php

namespace App\Http\Controllers\Backend\Role;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

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

        return redirect()->route('all.roles')->with($notification);
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

    public function AddRolesPermission()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = User::getpermissionGroup();

        return view('admin.rolesetup.add_roles_permission', compact('roles', 'permissions', 'permission_groups'));
    }



    public function RolePermissionStore(Request $request)
    {
        Log::info('Request Data:', $request->all());

        $data = array();
        $permissions = $request->permission;

        foreach ($permissions as $key => $item) {
            $data['role_id'] = $request->role_id;
            $data['permission_id'] = $item;

            DB::table('role_has_permissions')->insert($data);
        }

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Role Permissions Added Successfully!',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.role.permission')->with($notification);
    }

    public function AllRolePermission()
    {
        $roles = Role::all();
        return view('admin.rolesetup.all_role_permission', compact('roles'));
    }

    public function AdminEditRole($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $permission_groups = User::getpermissionGroup();
        return view('admin.rolesetup.admin_edit_role', compact('role', 'permissions', 'permission_groups'));
    }

    public function AdminUpdateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = $request->permission;

        // Log::info($request->permission);

        if (!empty($permissions)) {
            // Validasi izin
            $validPermissions = Permission::whereIn('name', $permissions)->pluck('name')->toArray();

            // Sinkronisasi izin yang valid saja
            $role->syncPermissions($validPermissions);
        }

        $notification = [
            'message' => 'Role Permissions Updated Successfully!',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.role.permission')->with($notification);
    }


    public function AdminDeleteRole($id)
    {
        try {
            // Cari paket berdasarkan ID
            $role = Role::find($id);

            if (!$role) {
                return redirect()->route('all.role.permission')->with([
                    'message' => 'Role not found!',
                    'alert-type' => 'error',
                ]);
            }

            // Hapus semua izin yang terkait dengan role
            $role->permissions()->detach();

            // Hapus role
            $role->delete();

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
