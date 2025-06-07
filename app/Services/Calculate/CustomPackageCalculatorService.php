<?php

namespace App\Services\Calculate;

use App\Models\Hotel;
use App\Models\Vehicle;
use App\Models\Facility;
use App\Models\Destination;
use Illuminate\Support\Facades\Log;

class CustomPackageCalculatorService
{
    /**
     * Menghitung total biaya paket wisata.
     *
     * @param array $data Data input dari request
     * @return array Hasil perhitungan
     */
    public function calculate(array $data): array
    {
        try {
            Log::info('Memulai perhitungan paket', ['data' => $data]);

            // Validasi data minimal
            if (empty($data['destinationIds']) || empty($data['facilityIds'])) {
                throw new \InvalidArgumentException('Destinasi atau fasilitas tidak boleh kosong');
            }

            // Ambil data dari database
            try {
                $vehicle = Vehicle::findOrFail($data['vehicleId']);
                $selectedDestinations = Destination::whereIn('id', $data['destinationIds'])->get();
                $selectedFacilities = Facility::whereIn('id', $data['facilityIds'])->get();

                Log::debug('Data referensi ditemukan', [
                    'vehicle' => $vehicle->name,
                    'destinations' => $selectedDestinations->count(),
                    'facilities' => $selectedFacilities->count()
                ]);
            } catch (\Exception $e) {
                throw new \RuntimeException('Gagal mengambil data referensi: ' . $e->getMessage());
            }

            // Perhitungan utama
            try {
                $transportCost = $this->calculateTransportCost($vehicle, $data['DurationPackage'], $data['participants']);
                [$totalTicketCostWNI, $totalTicketCostWNA, $parkingCost] = $this->calculateDestinationCosts($selectedDestinations, $data['participants'], $vehicle);
                $totalFacilityCost = $this->calculateFacilityCosts($selectedFacilities, $data['participants'], $data['DurationPackage']);
                $hotelCostData = $this->calculateAccommodationCosts(
                    $data['accommodationType'],
                    $data['hotelPrice'] ?? 0,
                    $data['selectedHotels'] ?? [],
                    $data['extraBedPrice'],
                    $data['capacityHotel'],
                    $data['participants'],
                    $data['night']
                );

                Log::debug('Hasil perhitungan parsial', [
                    'transportCost' => $transportCost,
                    'ticketCost' => $totalTicketCostWNI,
                    'parkingCost' => $parkingCost,
                    'facilityCost' => $totalFacilityCost,
                    'hotelCost' => $hotelCostData['totalHotelCost']
                ]);
            } catch (\Exception $e) {
                throw new \RuntimeException('Gagal melakukan perhitungan: ' . $e->getMessage());
            }

            // Perhitungan turunan
            try {
                $totalMealCost = $data['mealCost'] * $data['totalMeal'] * $data['participants'];
                $totalCost = $transportCost + $totalTicketCostWNI + $parkingCost + $totalFacilityCost +
                    $data['otherFee'] + $data['reservedFee'] + $hotelCostData['totalHotelCost'] + $totalMealCost;

                $downPayment = $totalCost * 0.30;
                $remainingCosts = $totalCost - $downPayment;
                $costPerPerson = ($data['participants'] > 0) ? $totalCost / $data['participants'] : 0;
                $childCost = $costPerPerson * 0.40;
                $additionalCostWna = ($data['participants'] > 0) ? ($totalTicketCostWNA - $totalTicketCostWNI) / $data['participants'] : 0;

                $result = [
                    'transportCost' => $transportCost,
                    'parkingCost' => $parkingCost,
                    'ticketCost' => $totalTicketCostWNI,
                    'hotelCost' => $hotelCostData['totalHotelCost'],
                    'extraBedCost' => $hotelCostData['totalExtraBedCost'],
                    'otherFee' => $data['otherFee'],
                    'reservedFee' => $data['reservedFee'],
                    'totalMealCost' => $totalMealCost,
                    'facilityCost' => $totalFacilityCost,
                    'totalCost' => $totalCost,
                    'DurationPackage' => $data['DurationPackage'],
                    'night' => $data['night'],
                    'downPayment' => $downPayment,
                    'remainingCosts' => $remainingCosts,
                    'costPerPerson' => $costPerPerson,
                    'childCost' => $childCost,
                    'participants' => $data['participants'],
                    'additionalCostWna' => $additionalCostWna,
                    'destinationNames' => $selectedDestinations->pluck('name')->toArray(),
                    'facilityNames' => $selectedFacilities->pluck('name')->toArray(),
                    'hotelNames' => $hotelCostData['hotelNames']
                ];

                Log::info('Perhitungan selesai', $result);
                return $result;
            } catch (\DivisionByZeroError $e) {
                throw new \RuntimeException('Jumlah peserta tidak boleh nol');
            } catch (\Exception $e) {
                throw new \RuntimeException('Gagal menghitung turunan: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Error dalam calculate: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Menghitung biaya transportasi berdasarkan jenis kendaraan dan durasi.
     * Termasuk perhitungan jumlah armada yang dibutuhkan.
     *
     * @param Vehicle $vehicle Model kendaraan
     * @param int $durationPackage Durasi paket dalam hari
     * @param int $participants Jumlah peserta
     * @return float Biaya transportasi
     */
    private function calculateTransportCost(Vehicle $vehicle, int $durationPackage, int $participants): float
    {
        // Hitung jumlah armada yang dibutuhkan
        $numArmada = ceil($participants / $vehicle->capacity_max);
        return $vehicle->price * $durationPackage * $numArmada;
    }

    /**
     * Menghitung biaya destinasi (tiket masuk dan parkir).
     *
     * @param \Illuminate\Support\Collection $destinations Koleksi model Destination
     * @param int $participants Jumlah peserta
     * @param Vehicle $vehicle Model kendaraan
     * @return array [totalCostWNI, totalCostWNA, parkingCost]
     */
    private function calculateDestinationCosts($destinations, int $participants, Vehicle $vehicle): array
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

    /**
     * Menghitung biaya fasilitas tambahan.
     *
     * @param \Illuminate\Support\Collection $facilities Koleksi model Facility
     * @param int $participants Jumlah peserta
     * @param int $DurationPackage Durasi paket dalam hari
     * @return float Total biaya fasilitas
     */
    private function calculateFacilityCosts($facilities, int $participants, int $DurationPackage): float
    {
        $totalFacilityCost = 0;
        $flatCost = 0;
        $shuttleCost = 0; // Ubah nama variabel
        $facPerdayCost = 0;
        $facPerpersonCost = 0;
        $facInfoCost = 0;
        $facEventCost = 0;
        $facDocCost = 0;
        $guideCost = 0;

        // Cek fasilitas 'flat' di seluruh $facilities (bukan $facilityIds)
        $hasFlat = $facilities->contains(function ($facility) {
            return $facility->type === 'flat';
        });

        // Hitung biaya berdasarkan fasilitas
        foreach ($facilities as $facility) {
            // Pastikan max_user tidak nol untuk menghindari division by zero
            $groupCount = ($facility->max_user > 0) ? ceil($participants / $facility->max_user) : 1;

            switch ($facility->type) {
                case 'flat':
                    // Biaya flat hanya dihitung sekali, tidak dikalikan durasi
                    $flatCost += $groupCount * $facility->price;
                    break;

                case 'shuttle':
                    // Hanya hitung jika peserta memenuhi syarat 18-55
                    if ($participants >= 18 && $participants <= 55) {
                        // Jika ada fasilitas 'flat', hitung biaya shuttle berdasarkan durasi
                        if ($hasFlat) {
                            if ($DurationPackage === 2) {
                                $shuttleCost += $groupCount * $facility->price; // x1
                            } elseif ($DurationPackage === 3) {
                                $shuttleCost += $groupCount * $facility->price * 2; // x2
                            } elseif ($DurationPackage === 4) {
                                $shuttleCost += $groupCount * $facility->price * 3; // x3
                            } elseif ($DurationPackage === 5) {
                                $shuttleCost += $groupCount * $facility->price * 4; // x4
                            }
                        } else {
                            // Jika tidak ada 'flat', hitung shuttle dengan logika default
                            $shuttleCost += $groupCount * $facility->price * $DurationPackage;
                        }
                    }
                    break;

                case 'per_day':
                    $facPerdayCost += $facility->price * $DurationPackage;
                    break;

                case 'doc':
                    $facDocCost += $facility->price * $DurationPackage;
                    break;

                case 'tl':
                    $guideCost += $groupCount * $facility->price * $DurationPackage;
                    break;

                case 'per_person':
                    $facPerpersonCost += $facility->price * $participants * $DurationPackage;
                    break;

                case 'event':
                    $facEventCost += $facility->price * $DurationPackage;
                    break;

                case 'info':
                    $facInfoCost += $facility->price * $DurationPackage;
                    break;
            }
        }

        $totalFacilityCost = $flatCost + $shuttleCost + $facPerdayCost + $facDocCost +
            $guideCost + $facPerpersonCost + $facEventCost + $facInfoCost;

        return $totalFacilityCost;
    }

    /**
     * Menghitung biaya akomodasi (hotel/penginapan).
     *
     * @param string $accommodationType Tipe akomodasi ('manual' atau 'advanced')
     * @param float $manualHotelPrice Harga hotel manual (jika tipe 'manual')
     * @param array $selectedHotelIds ID hotel yang dipilih (jika tipe 'advanced')
     * @param float $extraBedPrice Harga extrabed per unit
     * @param int $capacityHotel Kapasitas kamar standar
     * @param int $participants Jumlah peserta
     * @param int $night Jumlah malam menginap
     * @return array ['totalHotelCost', 'totalExtraBedCost', 'hotelNames']
     */
    private function calculateAccommodationCosts(
        string $accommodationType,
        float $manualHotelPrice,
        array $selectedHotelIds,
        float $extraBedPrice,
        int $capacityHotel,
        int $participants,
        int $night
    ): array {
        $totalHotelCost = 0;
        $totalExtraBedCost = 0;
        $hotelNames = [];

        if ($accommodationType === 'manual') {
            // Logika perhitungan hotel yang sudah ada
            $numRooms = floor($participants / $capacityHotel);
            $remainingParticipants = $participants % $capacityHotel;

            if ($capacityHotel > $participants) {
                $numRooms = 1;
                $remainingParticipants = 0;
                $totalHotelCost = $manualHotelPrice * $numRooms * $night;
            } else {
                $totalHotelCost = $manualHotelPrice * $numRooms * $night;
            }

            if ($remainingParticipants > 0) {
                if ($remainingParticipants <= 2) {
                    $totalExtraBedCost += ($remainingParticipants * $extraBedPrice * $night);
                } else {
                    // Jika sisa peserta lebih dari 2 dan perlu kamar tambahan
                    $totalHotelCost += $manualHotelPrice * $night; // Tambah biaya 1 kamar lagi
                    $remainingParticipants -= $capacityHotel; // Kurangi peserta yang sudah diakomodasi kamar baru

                    if ($remainingParticipants > 0) {
                        // Jika masih ada sisa, akomodasi dengan extra bed (maks 2)
                        $totalExtraBedCost += min($remainingParticipants, 2) * $extraBedPrice * $night;
                    }
                }
            }
            $totalHotelCost += $totalExtraBedCost; // Total biaya hotel termasuk extrabed
            $hotelNames[] = 'Manual Input Hotel (Price: ' . number_format($manualHotelPrice, 0, ',', '.') . ')';
        } elseif ($accommodationType === 'advanced') {
            $selectedHotels = Hotel::whereIn('id', $selectedHotelIds)->get();

            foreach ($selectedHotels as $hotel) {
                $hotelNames[] = $hotel->name . ' (' . $hotel->type . ')';
                $costPerPersonPerNight = $hotel->price / $hotel->capacity; // Harga per orang per malam dari hotel

                // Hitung berapa kamar yang dibutuhkan untuk hotel ini
                $numRoomsForThisHotel = ceil($participants / $hotel->capacity);

                // Biaya dasar kamar untuk hotel ini
                $hotelBaseCost = $numRoomsForThisHotel * $hotel->price * $night;

                // Hitung sisa peserta untuk extrabed
                $totalParticipantsForThisHotel = $numRoomsForThisHotel * $hotel->capacity;
                $currentRemainingParticipants = $participants - ($numRoomsForThisHotel * $hotel->capacity);

                // Jika ada sisa peserta, hitung extrabed
                if ($currentRemainingParticipants > 0) {
                    $extrabedsNeeded = min($currentRemainingParticipants, 2); // Maksimal 2 extrabed per kamar
                    $extrabedCostForThisHotel = $extrabedsNeeded * $hotel->extrabed_price * $night;
                    $totalExtraBedCost += $extrabedCostForThisHotel;
                }
                $totalHotelCost += $hotelBaseCost;
            }
        }
        return [
            'totalHotelCost' => $totalHotelCost,
            'totalExtraBedCost' => $totalExtraBedCost,
            'hotelNames' => $hotelNames
        ];
    }
}
