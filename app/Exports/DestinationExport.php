<?php

namespace App\Exports;

use App\Models\Destination;
use Maatwebsite\Excel\Concerns\FromCollection;

class DestinationExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Destination::select(
            'regency_id', 'name', 'price_wni', 'price_wna', 'price_type', 'max_participants', 'parking_city_car', 'parking_mini_bus', 'parking_bus', 'ket', 'status'
        )->get();
    }
}
