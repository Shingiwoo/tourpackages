<?php

namespace App\Imports;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;

class VehiclesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            return new Vehicle([
                'regency_id' => $row[0],
                'name' => $row[1],
                'type' => $row[2],
                'capacity_min' => $row[3],
                'capacity_max' => $row[4],
                'price' => $row[5],
                'status' => $row[6],

            ]);
        } catch (\Exception $e) {
            Log::error('Error processing row: ' . json_encode($row) . ' Error: ' . $e->getMessage());
            return null; // Skip invalid rows
        }
    }

    // public function rules(): array
    // {
    //     return [
    //         'regency_id' => 'required|exists:regencies,id',
    //         'name' => 'required|string|max:255',
    //         'type' => 'required|in:City Car,Mini Bus,Bus',
    //         'capacity_min' => 'required|string',
    //         'capacity_max' => 'required|string',
    //         'price' => 'required|string',
    //         'status' => 'required|boolean',
    //     ];
    // }

    // public function customValidationMessages()
    // {
    //     return [
    //         'regency_id.required' => 'Regency ID is required.',
    //         'regency_id.exists' => 'Regency ID must exist in the regencies table.',
    //         'name.required' => 'Vehicle name is required.',
    //         'type.in' => 'Type must be either City Car, Mini Bus or Bus.',
    //     ];
    // }
}
