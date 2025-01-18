<?php

namespace App\Http\Controllers\Backend\Custom;

use App\Models\Custom;
use App\Models\Regency;
use App\Models\Vehicle;
use App\Models\Facility;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CustomPackageController extends Controller
{
    public function CustomDashboard()
    {
        $destinations = Destination::all();
        $regencies = Regency::all();
        $vehicles = Vehicle::all();
        $facilities = Facility::all();

        $customPackage = Custom::first(); // Ambil data pertama
        $prices = $customPackage ? json_decode($customPackage->custompackage, true) : null;

        return view('admin.custom.index', compact('destinations', 'regencies', 'vehicles', 'facilities','prices'));
    }

    public function StoreData(Request $request)
    {
        Log::info('Mulai Menghitung Custom Paket!');
        // Validasi data input
        $validatedData = $request->validate([
            'facilities' => 'required|array',
            'facilities.*' => 'exists:facilities,id',
            'destinations' => 'required|array',
            'destinations.*' => 'exists:destinations,id',
            'DurationPackage' => 'required',
            'vehicleName' => 'required',
            'night' => 'required',
            'mealCost' => 'required',
            'totalMeal' => 'required',
            'otherFee' => 'required',
            'reservedFee' => 'required',
            'hotelPrice' => 'required',
            'capacityHotel' => 'required',
            'totalUser' => 'required',
        ]);

        Log::info('Cek Validasi:', $validatedData);

        $validatedData['otherFee'] = str_replace(',', '', $validatedData['otherFee']);
        $validatedData['mealCost'] = str_replace(',', '', $validatedData['mealCost']);
        $validatedData['reservedFee'] = str_replace(',', '', $validatedData['reservedFee']);
        $validatedData['hotelPrice'] = str_replace(',', '', $validatedData['hotelPrice']);

        // Ambil data dari request
        $destinationIds = $validatedData['destinations'];
        $facilityIds = $validatedData['facilities'];
        $vehicleId = $validatedData['vehicleName'];
        $hotelPrice = $validatedData['hotelPrice'];
        $otherFee = $validatedData['otherFee'];
        $DurationPackage = $validatedData['DurationPackage'];
        $night = $validatedData['night'];
        $reservedFee = $validatedData['reservedFee'];
        $capacityHotel = $validatedData['capacityHotel'];
        $participants = $validatedData['totalUser'];
        $mealCost = $validatedData['mealCost'];
        $totalMeal = $validatedData['totalMeal'];

        $vehicle = Vehicle::find($vehicleId);
        $selectedDestinations = Destination::whereIn('id', $destinationIds)->get();
        $selectedFacilities = Facility::whereIn('id', $facilityIds)->get();

        // Hitung harga
        $prices = $this->calculatePrices(
            $vehicle,
            $hotelPrice,
            $otherFee,
            $DurationPackage,
            $night,
            $reservedFee,
            $mealCost,
            $totalMeal,
            $selectedDestinations,
            $selectedFacilities,
            $capacityHotel,
            $participants
        );

        Log::info('Cek Prices data :', $prices);

        // Simpan data ke tabel customs
        $customData = Custom::first(); // Ambil data pertama
        if ($customData) {
            $customData->update(['custompackage' => json_encode($prices)]);
        } else {
            Custom::create(['custompackage' => json_encode($prices)]);
        }

        // Kirim notifikasi berhasil
        $notification = [
            'message' => 'Calculations Successfully!',
            'alert-type' => 'success',
        ];

        // Redirect ke halaman destinasi dengan notifikasi
        return redirect()->route('all.custom.package')->with($notification);
    }


    private function calculatePrices(
        $vehicle,
        $hotelPrice,
        $otherFee,
        $DurationPackage,
        $night,
        $reservedFee,
        $mealCost,
        $totalMeal,
        $selectedDestinations,
        $selectedFacilities,
        $capacityHotel,
        $participants
    ) {
        $transportCost = $vehicle->price * $DurationPackage;

        [$totalCostWNI, $totalCostWNA, $parkingCost] = $this->calculateDestinationCosts($selectedDestinations, $participants, $vehicle);

        $totalFacilityCost = $this->calculateFacilityCosts($selectedFacilities, $participants, $DurationPackage);

        $groupCount = ceil($participants / $capacityHotel);
        $totalHotelCost = $hotelPrice * $groupCount * $night;
        $totalMealCost = $mealCost * $totalMeal * $participants;

        $totalCost = $transportCost + $totalCostWNI + $parkingCost + $totalFacilityCost + $otherFee + $reservedFee + $totalHotelCost + $totalMealCost;

        $downPayment = $totalCost * 0.30;
        $remainingCosts = $totalCost - $downPayment;
        $costPerPerson = $totalCost / $participants;
        $childCost = $costPerPerson * 0.40;
        $additionalCostWna = ($totalCostWNA - $totalCostWNI) / $participants;

        // Ambil nama destinasi dan fasilitas
        $destinationNames = $selectedDestinations->pluck('name')->toArray();
        $facilityNames = $selectedFacilities->pluck('name')->toArray();

        return [
            'transportCost' => $transportCost,
            'parkingCost' => $parkingCost,
            'ticketCost' => $totalCostWNI,
            'hotelCost' => $totalHotelCost,
            'otherFee' => $otherFee,
            'reservedFee' => $reservedFee,
            'totalMealCost' => $totalMealCost,
            'facilityCost' => $totalFacilityCost,
            'totalCost' => $totalCost,
            'DurationPackage' => $DurationPackage,
            'night' => $night,
            'downPayment' => $downPayment,
            'remainingCosts' => $remainingCosts,
            'costPerPerson' => $costPerPerson,
            'childCost' => $childCost,
            'participants' => $participants,
            'additionalCostWna' => $additionalCostWna,
            'destinationNames' => $destinationNames,
            'facilityNames' => $facilityNames,
        ];
    }

    private function calculateDestinationCosts($destinations, $participants, $vehicle)
    {
        $totalCostWNI = 0;
        $totalCostWNA = 0;
        $parkingCost = 0;

        foreach ($destinations as $destination) {
            if ($destination->price_type === 'per_person') {
                $totalCostWNI += $destination->price_wni * $participants;
                $totalCostWNA += $destination->price_wna * $participants;
            } elseif ($destination->price_type === 'flat') {
                $groupCount = ceil($participants / $destination->max_participants);
                $totalCostWNI += $groupCount * $destination->price_wni;
                $totalCostWNA += $groupCount * $destination->price_wna;
            }

            $parkingCosts = [
                'City Car' => $destination->parking_city_car,
                'Mini Bus' => $destination->parking_mini_bus,
                'Bus' => $destination->parking_bus,
            ];

            $parkingCost += $parkingCosts[$vehicle->type] ?? 0;
        }

        return [$totalCostWNI, $totalCostWNA, $parkingCost];
    }

    private function calculateFacilityCosts($facilityIds, $participants, $DurationPackage)
    {
        // Inisialisasi biaya fasilitas
        $totalFacilityCost = 0;
        $ShuttleCost = 0;
        $flatCost = 0;
        $facPerdayCost = 0;
        $facPerpersonCost = 0;
        $facInfoCost = 0;
        $facEventCost = 0;
        $facDocCost = 0;
        $guideCost = 0;

        foreach ($facilityIds as $facility) {
            $groupCount = ceil($participants / ($facility->max_user ?? $participants)); // Hitung grup berdasarkan max_user

            switch ($facility->type) {

                case 'flat':
                    // Hitung biaya flat
                    $flatCost += $groupCount * $facility->price;

                    // Hitung biaya flat / ShuttleCost jika ada nilainya
                    if ($DurationPackage === '2') {
                        // Hitung biaya flat
                        $flatCost += $groupCount * $facility->price;

                        // Jika memenuhi syarat shuttle, hitung biaya shuttle
                        if ($participants >= 18 && $participants <= 55 && $facility->type === 'shuttle') {
                            $ShuttleCost += $groupCount * $facility->price;
                        }
                        break;
                    } elseif ($DurationPackage === '3') {
                        // Hitung biaya flat
                        $flatCost += $groupCount * $facility->price;

                        // Jika memenuhi syarat shuttle, hitung biaya shuttle
                        if ($participants >= 18 && $participants <= 55 && $facility->type === 'shuttle') {
                            $ShuttleCost += $groupCount * $facility->price * 2;
                        }
                        break;
                    } elseif ($DurationPackage === '4') {
                        // Hitung biaya flat
                        $flatCost += $groupCount * $facility->price;

                        // Jika memenuhi syarat shuttle, hitung biaya shuttle
                        if ($participants >= 18 && $participants <= 55 && $facility->type === 'shuttle') {
                            $ShuttleCost += $groupCount * $facility->price * 3;
                        }
                        break;
                    } elseif ($DurationPackage === '5') {
                        // Hitung biaya flat
                        $flatCost += $groupCount * $facility->price;

                        // Jika memenuhi syarat shuttle, hitung biaya shuttle
                        if ($participants >= 18 && $participants <= 55 && $facility->type === 'shuttle') {
                            $ShuttleCost += $groupCount * $facility->price * 4;
                        }
                        break;
                    }

                case 'shuttle':
                    // Hitung biaya shuttle dengan syarat peserta
                    if ($participants >= 18 && $participants <= 55) {
                        $ShuttleCost += $groupCount * $facility->price * $DurationPackage;
                    }
                    break;

                case 'per_day':
                    // Hitung biaya per hari
                    $facPerdayCost += $facility->price * $DurationPackage;
                    break;

                case 'doc':
                    // Hitung biaya doc jika peserta dalam rentang 20-55
                    if ($participants >= 20 && $participants <= 55) {
                        $facDocCost += $facility->price * $DurationPackage;
                    }
                    break;

                case 'tl':
                    // Hitung biaya guide jika peserta dalam rentang 20-55
                    if ($participants >= 20 && $participants <= 55) {
                        $guideCost += $groupCount * $facility->price * $DurationPackage;
                    }
                    break;

                case 'per_person':
                    // Hitung biaya per orang
                    $facPerpersonCost += $facility->price * $participants * $DurationPackage;
                    break;

                case 'event':
                    // Hitung biaya event
                    $facEventCost += $facility->price * $DurationPackage;
                    break;

                case 'info':
                    // Hitung biaya info
                    $facInfoCost += $facility->price * $DurationPackage;
                    break;
            }

            $totalFacilityCost = $flatCost + $ShuttleCost + $facPerdayCost + $facDocCost + $guideCost + $facPerpersonCost + $facEventCost + $facInfoCost;
        }

        return $totalFacilityCost;
    }
}
