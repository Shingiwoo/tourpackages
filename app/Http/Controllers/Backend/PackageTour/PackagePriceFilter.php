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
            $priceGroups = is_string($package->prices->prices)
                ? json_decode($package->prices->prices, true)
                : $package->prices->prices;

            $groupedPrices = [];
            $priceTypes = [];
            $accommodationTypes = ['WithoutAccomodation', 'Cottage', 'Homestay', 'Villa', 'Guesthouse', 'ThreeStar', 'FourStar'];

            foreach ($priceGroups as $group) {
                if (!isset($group['Price Type']) || !isset($group['data']) || !is_array($group['data'])) {
                    continue;
                }

                $type = $group['Price Type'];
                $priceTypes[] = $type;
                $groupedPrices[$type] = [];

                foreach ($group['data'] as $price) {
                    if (!isset($price['user'])) continue;

                    $matchesFilter = false;
                    if ($min_peserta && $max_peserta) {
                        $matchesFilter = $price['user'] >= $min_peserta && $price['user'] <= $max_peserta;
                    } elseif ($min_peserta) {
                        $matchesFilter = $price['user'] == $min_peserta;
                    } elseif ($max_peserta) {
                        $matchesFilter = $price['user'] == $max_peserta;
                    } else {
                        $matchesFilter = true;
                    }

                    if ($matchesFilter) {
                        $participantCount = $price['user'];
                        $filteredPrice = [
                            'vehicle' => $price['vehicle'] ?? '-'
                        ];

                        foreach ($accommodationTypes as $accType) {
                            if (isset($price[$accType])) {
                                $filteredPrice[$accType] = $price[$accType];
                            }
                        }

                        $groupedPrices[$type][$participantCount] = $filteredPrice;
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
                'price_types' => array_unique($priceTypes),
                'created_at' => $package->created_at->toDateTimeString(),
                'updated_at' => $package->updated_at->toDateTimeString(),
            ];
        });
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

    public function allDataOneDay()
    {
        // Ambil semua paket oneday
        $oneDay = PackageOneDay::with(['destinations', 'facilities', 'prices'])->paginate(4);

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
        $twoDay = PackageTwoDay::with(['destinations', 'facilities', 'prices'])->paginate(4);

        // Urai price_data untuk setiap item
        $twoDay->getCollection()->transform(function ($package) {
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
                "message"   => "List Data Posts Twodays"
            ],
            "data" => $twoDay
        ], 200);
    }

    public function allDataThreeDay()
    {
        // Ambil semua paket ThreeDay
        $threeDay = PackageThreeDay::with(['destinations', 'facilities', 'prices'])->paginate(4);

        // Urai price_data untuk setiap item
        $threeDay->getCollection()->transform(function ($package) {
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
                "message"   => "List Data Posts ThreeDays"
            ],
            "data" => $threeDay
        ], 200);
    }

    public function allDataFourDay()
    {
        // Ambil semua paket FourDay
        $fourDay = PackageFourDay::with(['destinations', 'facilities', 'prices'])->paginate(4);

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
