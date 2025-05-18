<?php

namespace App\Http\Controllers\Backend\PackageTour;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PackageOneDay;
use App\Models\PackageTwoDay;
use App\Models\PackageFourDay;
use App\Models\PackageThreeDay;
use App\Http\Controllers\Controller;

class PackagePriceFilter extends Controller
{

    /**
     * Display the filter form and handle filtering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function filter(Request $request)
    {
        $agens = User::where('role', 'agen')->get();

        // Get filter parameters
        $min_peserta = $request->input('min_peserta');
        $max_peserta = $request->input('max_peserta');
        $durasi_paket = $request->input('durasi_paket');
        $agen_name = $request->input('agen_name');

        // Initialize packages variable
        $packages = null;

        // If filters are provided, fetch filtered data
        if ($request->hasAny(['min_peserta', 'max_peserta', 'durasi_paket', 'agen_name'])) {
            switch ($durasi_paket) {
                case '1':
                    $packages = $this->filterOneDayPackages($min_peserta, $max_peserta, $agen_name);
                    break;
                case '2':
                    $packages = $this->filterTwoDayPackages($min_peserta, $max_peserta, $agen_name);
                    break;
                case '3':
                    $packages = $this->filterThreeDayPackages($min_peserta, $max_peserta, $agen_name);
                    break;
                case '4':
                    $packages = $this->filterFourDayPackages($min_peserta, $max_peserta, $agen_name);
                    break;
            }
        }

        return view('admin.package.filter_price', compact('agens', 'packages'));
    }

    private function filterOneDayPackages($min_peserta, $max_peserta, $agen_name)
    {
        $query = PackageOneDay::with(['destinations', 'facilities', 'prices']);

        if ($agen_name) {
            $query->where('agen_id', $agen_name);
        }

        $packages = $query->get();

        return $this->filterPackagesByParticipants($packages, $min_peserta, $max_peserta);
    }

    private function filterTwoDayPackages($min_peserta, $max_peserta, $agen_name)
    {
        $query = PackageTwoDay::with(['destinations', 'facilities', 'prices']);

        if ($agen_name) {
            $query->where('agen_id', $agen_name);
        }

        $packages = $query->get();

        return $this->filterMultiDayPackages($packages, $min_peserta, $max_peserta);
    }

    private function filterThreeDayPackages($min_peserta, $max_peserta, $agen_name)
    {
        $query = PackageThreeDay::with(['destinations', 'facilities', 'prices']);

        if ($agen_name) {
            $query->where('agen_id', $agen_name);
        }

        $packages = $query->get();

        return $this->filterMultiDayPackages($packages, $min_peserta, $max_peserta);
    }

    private function filterFourDayPackages($min_peserta, $max_peserta, $agen_name)
    {
        $query = PackageFourDay::with(['destinations', 'facilities', 'prices']);

        if ($agen_name) {
            $query->where('agen_id', $agen_name);
        }

        $packages = $query->get();

        return $this->filterMultiDayPackages($packages, $min_peserta, $max_peserta);
    }

    private function filterPackagesByParticipants($packages, $min_peserta, $max_peserta)
    {
        return $packages->filter(function ($package) use ($min_peserta, $max_peserta) {
            if (!$package->prices || empty($package->prices->price_data)) {
                return false;
            }

            $priceData = $package->prices->price_data;
            if (is_string($priceData)) {
                $priceData = json_decode($priceData, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return false;
                }
            }

            // Check if any price matches the filter criteria
            foreach ($priceData as $price) {
                if (!isset($price['user'])) continue;

                if ($min_peserta && $max_peserta) {
                    if ($price['user'] >= $min_peserta && $price['user'] <= $max_peserta) {
                        return true;
                    }
                } elseif ($min_peserta) {
                    if ($price['user'] == $min_peserta) {
                        return true;
                    }
                } elseif ($max_peserta) {
                    if ($price['user'] == $max_peserta) {
                        return true;
                    }
                } else {
                    return true;
                }
            }

            return false;
        })->map(function ($package) use ($min_peserta, $max_peserta) {
            $priceData = $package->prices->price_data;
            if (is_string($priceData)) {
                $priceData = json_decode($priceData, true);
            }

            $filteredPrices = array_filter($priceData, function ($price) use ($min_peserta, $max_peserta) {
                if (!isset($price['user'])) return false;

                if ($min_peserta && $max_peserta) {
                    return $price['user'] >= $min_peserta && $price['user'] <= $max_peserta;
                } elseif ($min_peserta) {
                    return $price['user'] == $min_peserta;
                } elseif ($max_peserta) {
                    return $price['user'] == $max_peserta;
                }
                return true;
            });

            return [
                'id' => $package->id,
                'name_package' => $package->name_package,
                'agen_id' => $package->agen_id,
                'status' => $package->status,
                'information' => $package->information,
                'destinations' => $package->destinations->map(function ($destination) {
                    return ['name' => $destination->name];
                })->toArray(),
                'facilities' => $package->facilities->map(function ($facility) {
                    return ['name' => $facility->name];
                })->toArray(),
                'prices' => array_values($filteredPrices), // Reset array keys
                'created_at' => $package->created_at->toDateTimeString(),
                'updated_at' => $package->updated_at->toDateTimeString(),
            ];
        });
    }

    /**
     * Filter multi-day packages based on participant count.
     *
     * @param  \Illuminate\Support\Collection  $packages
     * @param  int|null  $min_peserta
     * @param  int|null  $max_peserta
     * @return \Illuminate\Support\Collection
     */
    private function filterMultiDayPackages($packages, $min_peserta, $max_peserta)
    {
        return $packages->filter(function ($package) use ($min_peserta, $max_peserta) {
            if (!$package->prices || empty($package->prices->prices)) {
                return false;
            }

            $priceData = $this->extractPriceData($package->prices->prices);
            if (empty($priceData)) return false;

            foreach ($priceData as $price) {
                if (!isset($price['user'])) continue;

                if ($min_peserta && $max_peserta) {
                    if ($price['user'] >= $min_peserta && $price['user'] <= $max_peserta) {
                        return true;
                    }
                } elseif ($min_peserta) {
                    if ($price['user'] == $min_peserta) {
                        return true;
                    }
                } elseif ($max_peserta) {
                    if ($price['user'] == $max_peserta) {
                        return true;
                    }
                } else {
                    return true;
                }
            }

            return false;
        })->map(function ($package) use ($min_peserta, $max_peserta) {
            $priceData = $this->extractPriceData($package->prices->prices);
            $priceTypes = $this->getPriceTypes($package->prices->prices);

            // First filter by participant count
            $filteredPrices = array_filter($priceData, function ($price) use ($min_peserta, $max_peserta) {
                if (!isset($price['user'])) return false;

                if ($min_peserta && $max_peserta) {
                    return $price['user'] >= $min_peserta && $price['user'] <= $max_peserta;
                } elseif ($min_peserta) {
                    return $price['user'] == $min_peserta;
                } elseif ($max_peserta) {
                    return $price['user'] == $max_peserta;
                }
                return true;
            });

            // Then group by type and participant count
            $groupedPrices = [];
            foreach ($priceTypes as $type) {
                $groupedPrices[$type] = [];

                // Get all prices for this type
                $typePrices = array_filter($filteredPrices, function ($price) use ($type, $package) {
                    return $this->priceBelongsToType($price, $type, $package->prices->prices);
                });

                // Group by participant count
                foreach ($typePrices as $price) {
                    $participantCount = $price['user'];
                    if (!isset($groupedPrices[$type][$participantCount])) {
                        $groupedPrices[$type][$participantCount] = $price;
                    }
                }
            }

            return [
                'id' => $package->id,
                'name_package' => $package->name_package,
                'agen_id' => $package->agen_id,
                'status' => $package->status,
                'information' => $package->information,
                'destinations' => $package->destinations->map(function ($destination) {
                    return ['name' => $destination->name];
                })->toArray(),
                'facilities' => $package->facilities->map(function ($facility) {
                    return ['name' => $facility->name];
                })->toArray(),
                'grouped_prices' => $groupedPrices,
                'price_types' => $priceTypes,
                'created_at' => $package->created_at->toDateTimeString(),
                'updated_at' => $package->updated_at->toDateTimeString(),
            ];
        });
    }

    /**
     * Check if the price belongs to the specified type.
     *
     * @param  array  $price
     * @param  string  $priceType
     * @param  array|string  $allPrices
     * @return bool
     */
    private function priceBelongsToType($price, $priceType, $allPrices)
    {
        if (is_string($allPrices)) {
            $allPrices = json_decode($allPrices, true);
        }

        foreach ($allPrices as $group) {
            if (!isset($group['Price Type']) || $group['Price Type'] != $priceType) {
                continue;
            }

            if (!isset($group['data']) || !is_array($group['data'])) {
                continue;
            }

            foreach ($group['data'] as $groupPrice) {
                if (!isset($groupPrice['user']) || !isset($groupPrice['vehicle'])) {
                    continue;
                }

                // Match by user count, vehicle, and all relevant fields
                $match = true;
                $match = $match && ($groupPrice['user'] == $price['user']);
                $match = $match && ($groupPrice['vehicle'] == $price['vehicle']);

                // Compare all accommodation types
                $accommodationTypes = ['WithoutAccomodation', 'Homestay', 'ThreeStar'];
                foreach ($accommodationTypes as $type) {
                    if (isset($groupPrice[$type]) && isset($price[$type])) {
                        $match = $match && ($groupPrice[$type] == $price[$type]);
                    }
                }

                if ($match) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Extract price data from the prices array.
     *
     * @param  array|string  $prices
     * @return array
     */
    private function extractPriceData($prices)
    {
        $priceData = [];

        if (is_string($prices)) {
            $prices = json_decode($prices, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [];
            }
        }

        if (!is_array($prices)) {
            return [];
        }

        foreach ($prices as $priceGroup) {
            if (!isset($priceGroup['data']) || !is_array($priceGroup['data'])) {
                continue;
            }

            foreach ($priceGroup['data'] as $price) {
                if (isset($price['user']) && is_array($price)) {
                    $priceData[] = $price;
                }
            }
        }

        return $priceData;
    }

    /**
     * Get unique price types from the prices array.
     *
     * @param  array|string  $prices
     * @return array
     */
    private function getPriceTypes($prices)
    {
        if (is_string($prices)) {
            $prices = json_decode($prices, true);
        }

        $types = [];
        foreach ($prices as $priceGroup) {
            if (isset($priceGroup['Price Type'])) {
                $types[] = $priceGroup['Price Type'];
            }
        }

        return array_unique($types);
    }

    public function allDataOneDay()
    {
        // Ambil semua paket oneday
        $oneDay = PackageOneDay::with(['destinations', 'facilities', 'prices'])->paginate(5);

        // Urai price_data untuk setiap item
        $oneDay->getCollection()->transform(function ($package) {
            return [
                'id' => $package->id,
                'name_package' => $package->name_package,
                'agen_id' => $package->agen_id,
                'status' => $package->status,
                'information' => $package->information,
                'destinations' => $package->destinations->map(function ($destination) {
                    return [
                        'name' => $destination->name,
                    ];
                })->toArray(),
                'facilities' => $package->facilities->map(function ($facility) {
                    return [
                        'name' => $facility->name,
                    ];
                })->toArray(),
                'prices' => $package->prices ? $package->prices->prices : [],
                'created_at' => $package->created_at->toDateTimeString(),
                'updated_at' => $package->updated_at->toDateTimeString(),
            ];
        });

        return response()->json([
            "response" => [
                "status"    => 200,
                "message"   => "List Data Posts"
            ],
            "data" => $oneDay
        ], 200);
    }

    public function allDataTwoDay()
    {
        // Ambil semua paket twoday
        $oneDay = PackageTwoDay::with(['destinations', 'facilities', 'prices'])->paginate(1);

        // Urai price_data untuk setiap item
        $oneDay->getCollection()->transform(function ($package) {
            return [
                'id' => $package->id,
                'name_package' => $package->name_package,
                'agen_id' => $package->agen_id,
                'status' => $package->status,
                'information' => $package->information,
                'destinations' => $package->destinations->map(function ($destination) {
                    return [
                        'name' => $destination->name,
                    ];
                })->toArray(),
                'facilities' => $package->facilities->map(function ($facility) {
                    return [
                        'name' => $facility->name,
                    ];
                })->toArray(),
                'prices' => $package->prices ? $this->mapPrices($package->prices->prices) : [],
                'created_at' => $package->created_at->toDateTimeString(),
                'updated_at' => $package->updated_at->toDateTimeString(),
            ];
        });

        return response()->json([
            "response" => [
                "status"    => 200,
                "message"   => "List Data Posts"
            ],
            "data" => $oneDay
        ], 200);
    }

    public function allDataThreeDay()
    {
        // Ambil semua paket ThreeDay
        $oneDay = PackageThreeDay::with(['destinations', 'facilities', 'prices'])->paginate(1);

        // Urai price_data untuk setiap item
        $oneDay->getCollection()->transform(function ($package) {
            return [
                'id' => $package->id,
                'name_package' => $package->name_package,
                'agen_id' => $package->agen_id,
                'status' => $package->status,
                'information' => $package->information,
                'destinations' => $package->destinations->map(function ($destination) {
                    return [
                        'name' => $destination->name,
                    ];
                })->toArray(),
                'facilities' => $package->facilities->map(function ($facility) {
                    return [
                        'name' => $facility->name,
                    ];
                })->toArray(),
                'prices' => $package->prices ? $this->mapPrices($package->prices->prices) : [],
                'created_at' => $package->created_at->toDateTimeString(),
                'updated_at' => $package->updated_at->toDateTimeString(),
            ];
        });

        return response()->json([
            "response" => [
                "status"    => 200,
                "message"   => "List Data Posts"
            ],
            "data" => $oneDay
        ], 200);
    }

    public function allDataFourDay()
    {
        // Ambil semua paket FourDay
        $fourDay = PackageFourDay::with(['destinations', 'facilities', 'prices'])->paginate(1);

        // Urai price_data untuk setiap item
        $fourDay->getCollection()->transform(function ($package) {
            return [
                'id' => $package->id,
                'name_package' => $package->name_package,
                'agen_id' => $package->agen_id,
                'status' => $package->status,
                'information' => $package->information,
                'destinations' => $package->destinations->map(function ($destination) {
                    return [
                        'name' => $destination->name,
                    ];
                })->toArray(),
                'facilities' => $package->facilities->map(function ($facility) {
                    return [
                        'name' => $facility->name,
                    ];
                })->toArray(),
                'prices' => $package->prices ? $this->mapPrices($package->prices->prices) : [],
                'created_at' => $package->created_at->toDateTimeString(),
                'updated_at' => $package->updated_at->toDateTimeString(),
            ];
        });

        return response()->json([
            "response" => [
                "status"    => 200,
                "message"   => "List Data Posts"
            ],
            "data" => $fourDay
        ], 200);
    }


    /**
     * Map prices data to remove redundant Price Type entries in data array.
     *
     * @param  array  $prices
     * @return array
     */
    private function mapPrices(array $prices): array
    {
        return array_map(function ($priceGroup) {
            // Filter data array to exclude entries that only contain Price Type
            $filteredData = array_filter($priceGroup['data'], function ($item) {
                return !(isset($item['Price Type']) && count($item) === 1);
            });

            return [
                'Price Type' => $priceGroup['Price Type'],
                'data' => array_values($filteredData), // Reset array keys
            ];
        }, $prices);
    }
}
