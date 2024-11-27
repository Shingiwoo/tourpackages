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
                'regency_id' => $row['regency_id'],
                'name' => $row['name'],
                'status' => $row['status'],
                'type' => $row['type'],
                'price' => $row['price'],
                'capacity_min' => $row['capacity_min'],
                'capacity_max' => $row['capacity_max'],

            ]);
        } catch (\Exception $e) {
            Log::error('Error processing row: ' . json_encode($row) . ' Error: ' . $e->getMessage());
            return null; // Skip invalid rows
        }
    }

    public function rules(): array
    {
        return [
            'regency_id' => 'required|exists:regencies,id',
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'type' => 'required|in:City Car,Mini Bus,Bus',
            'price' => 'required|string',
            'capacity_min' => 'required|string',
            'capacity_max' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'regency_id.required' => 'Regency ID is required.',
            'regency_id.exists' => 'Regency ID must exist in the regencies table.',
            'name.required' => 'Vehicle name is required.',
            'type.in' => 'Type must be either City Car, Mini Bus or Bus.',
        ];
    }
}
