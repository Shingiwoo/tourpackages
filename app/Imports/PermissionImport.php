<?php

namespace App\Imports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Spatie\Permission\Models\Permission;

class PermissionImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            return new Permission([
                'name' => $row[0],
                'group_name' => $row[1],

            ]);
        } catch (\Exception $e) {
            Log::error('Error processing row: ' . json_encode($row) . ' Error: ' . $e->getMessage());
            return null; // Skip invalid rows
        }
    }
}
