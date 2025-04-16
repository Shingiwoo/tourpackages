<?php

namespace App\Imports;

use App\Models\Destination;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DestinationsImport implements ToModel, WithValidation, WithHeadingRow
{
    public function model(array $row)
    {
        try {
            return new Destination([
                'regency_id' => $row['regency_id'],
                'name' => $row['name'],
                'price_type' => $row['price_type'],
                'price_wni' => $row['price_wni'],
                'price_wna' => $row['price_wna'],
                'status' => $row['status'],
                'max_participants' => $row['max_participants'],
                'parking_city_car' => $row['parking_city_car'],
                'parking_mini_bus' => $row['parking_mini_bus'],
                'parking_bus' => $row['parking_bus'],
                'ket' => $row['ket'],
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
            'price_type' => 'required|in:flat,per_person',
            'price_wni' => 'nullable|numeric',
            'price_wna' => 'nullable|numeric',
            'status' => 'required|boolean',
            'max_participants' => 'nullable|integer|min:1',
            'parking_city_car' => 'nullable|numeric',
            'parking_mini_bus' => 'nullable|numeric',
            'parking_bus' => 'nullable|numeric',
            'ket' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'regency_id.required' => 'Regency ID is required.',
            'regency_id.exists' => 'Regency ID must exist in the regencies table.',
            'name.required' => 'Destination name is required.',
            'price_type.in' => 'Price type must be either flat or per_person.',
        ];
    }
}



