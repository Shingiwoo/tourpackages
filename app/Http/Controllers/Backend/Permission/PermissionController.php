<?php

namespace App\Http\Controllers\Backend\Permission;

use Illuminate\Http\Request;
use App\Exports\PermissionExport;
use App\Imports\PermissionImport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function AllPermissions()
    {
        $permissions = Permission::latest()->get();

        return view('admin.permission.all_permission', compact('permissions'));
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
        return redirect()->route('all.permissions')->with($notification);
    }

    public function EditPermission($id)
    {
        $permission = Permission::findOrFail($id);

        return view('admin.permission.edit_permission', compact('permission'));
    }

    public function UpdatePermission(Request $request, $id)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'permissionName' => 'required|string|max:255|unique:permissions,name,' . $id,
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

        return redirect()->route('all.permissions')->with($notification);
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

    public function PageImportPermission(){

        return view('admin.permission.import_permission');
    }

    public function Export()
    {
        return Excel::download(new PermissionExport, 'permissions.xlsx');
    }

    public function Import(Request $request)
    {
        try {
            if (!$request->hasFile('importFile')) {
                return redirect()->back()->with([
                    'message' => 'No file uploaded. Please upload a valid CSV or Excel file.',
                    'alert-type' => 'error',
                ]);
            }

            $file = $request->file('importFile');

            Log::info('File uploaded successfully: ' . $file->getClientOriginalName());

            // Validasi tipe file
            $validator = Validator::make($request->all(), [
                'importFile' => 'required|file|mimes:csv,xlsx',
            ]);

            if ($validator->fails()) {
                Log::warning('File validation failed.');
                return redirect()->back()->with([
                    'message' => 'Invalid file format. Only CSV or Excel files are allowed.',
                    'alert-type' => 'error',
                ]);
            }

            Log::info('File validation passed. Starting import.');

            // Proses file
            Excel::import(new PermissionImport, $file);

            Log::info('Excel import process completed.');

            // Kirim notifikasi berhasil
            $notification = [
                'message' => 'Permission Import Updated',
                'alert-type' => 'success',
            ];

            return redirect()->route('all.permissions')->with($notification);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            Log::error('Validation errors in Excel file: ' . json_encode($e->failures()));
            $failures = $e->failures();
            $errorMessages = '';
            foreach ($failures as $failure) {
                $errorMessages .= "Row {$failure->row()}: " . implode(', ', $failure->errors()) . "\n";
            }

            return redirect()->back()->with([
                'message' => "Import failed:\n$errorMessages",
                'alert-type' => 'error',
            ]);
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => 'An error occurred during import: ' . $e->getMessage(),
                'alert-type' => 'error',
            ]);
        }
    }
}
