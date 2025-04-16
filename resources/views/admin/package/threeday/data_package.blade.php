@extends('admin.admin_dashboard')
@section('admin')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-4">
            <h5 class="mb-1">All Data Threeday Packages For - <span class="text-uppercase">{{ $agen->username }}</span></h5>
            <p class="card-subtitle"></p>
        </div>
        <div class="dt-action-buttons text-end">
            <div class="dt-buttons btn-group flex-wrap">
                <div class="button-group">
                    <a class="btn btn-secondary" href="{{ route('all.threeday.packages') }}">All Package</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        @foreach ($packages as $key => $pack)
        <div class="col-lg-6 order-md-0 order-lg-0 mb-6">
            <div class="card h-100">
                <div class="card-header pb-0 d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h4 class="mb-1 text-uppercase">{{ $pack->name_package }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center g-md-8">
                        <div class="col-12 col-md-12 d-flex flex-column">
                            <div class="d-flex gap-1 align-items-center mb-3 flex-wrap">
                                <div class="col-lg-12 mb-4 mb-xl-0">
                                    <h6 class="fw-medium text-info">Destinations :</h6>
                                    <div class="demo-inline-spacing mt-4">
                                        <ol class="list-group list-group-numbered">
                                            @foreach ($pack->destinations as $desti)
                                            <li class="list-group-item">{{ $desti->name }}</li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2 mt-4">
                                <h6 class="text-warning">Facility :</h6>
                                <div class="demo-inline-spacing mt-4">
                                    <ol class="list-group">
                                        @forelse ($pack->facilities as $facility)
                                        <li class="list-group-item list-group-item-action waves-effect waves-light">{{ $facility->name }}</li>
                                        @empty
                                        <li class="list-group-item list-group-item-action waves-effect waves-light">No facilities available</li>
                                        @endforelse
                                    </ol>
                                </div>
                            </div>
                            <div class="mb-2 mt-4">
                                <h6 class="text-warning">Information :</h6>
                                <small class="fw-medium">{{ strip_tags($pack->information) }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="border rounded p-5 mt-5">
                        <div class="row gap-4 gap-sm-0">
                            <div class="col-12 col-sm-6">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="badge rounded bg-label-info p-1"><i class="ti ti-chart-pie-2 ti-sm"></i>
                                    </div>
                                    <h6 class="mb-0 fw-normal">Location</h6>
                                </div>
                                <h6 class="my-3 text-uppercase">{{ $pack->regency->name ?? "No Data" }}</h6>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="badge rounded bg-label-danger p-1">
                                        <i class="ti ti-brand-paypal ti-sm"></i>
                                    </div>
                                    <h6 class="mb-0 fw-normal">Status</h6>
                                </div>
                                <h6 class="my-3 text-uppercase">
                                    @if ($pack->status)
                                    Active
                                    @else
                                    Inactive
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>

                    <div class="accordion mt-4" id="accordionWithIcon">
                        <div class="accordion-item card bg-secondary">
                            <h2 class="accordion-header d-flex align-items-center">
                                <button type="button" class="accordion-button collapsed btn-secondary" data-bs-toggle="collapse"
                                    data-bs-target="#accordionWithIcon-2" aria-expanded="false">
                                    <i class="me-2 ti ti-receipt-2"></i>
                                    Price List
                                </button>
                            </h2>
                            <div id="accordionWithIcon-2" class="accordion-collapse collapse">
                                <div class="accordion-body">

                                    <div class="mt-2">
                                        <div class="table-responsive text-nowrap">
                                            <table class="table table-bordered">
                                                @php
                                                // Decode JSON dari database
                                                $prices = json_decode($package->prices->price_data, true);

                                                // Ambil daftar jenis akomodasi secara dinamis
                                                $accommodationTypes = [];
                                                if (is_array($prices) && count($prices) > 0) {
                                                    foreach ($prices as $group) {
                                                        if (!empty($group['data']) && is_array($group['data'])) {
                                                            foreach ($group['data'] as $priceRow) {
                                                                foreach ($priceRow as $key => $value) {
                                                                    if (!in_array($key, ['vehicle', 'user', 'wnaCost', 'mealCostPerPerson', 'Price Type'])) {
                                                                        $accommodationTypes[$key] = ucwords(str_replace(
                                                                            ['WithoutAccomodation', 'Guesthouse', 'Homestay', 'TwoStar', 'ThreeStar', 'FourStar', 'FiveStar'],
                                                                            ['Without Accommodation', 'Guesthouse', 'Homestay', 'Two Star', 'Three Star', 'Four Star', 'Five Star'],
                                                                            $key
                                                                        ));
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                @endphp

                                                @if (is_array($prices) && count($prices) > 0)
                                                    @foreach ($prices as $group)
                                                        @php
                                                            $priceType = $group['Price Type'] ?? 'Unknown';
                                                        @endphp

                                                        <!-- Header Baru untuk Setiap Tipe Harga -->
                                                        <thead class="align-content-center text-center">
                                                            <tr>
                                                                <th colspan="{{ 4 + count($accommodationTypes) }}" class="text-center">{{ $priceType }}</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="align-content-center text-center">Price Type</th>
                                                                <th class="align-content-center text-center">Vehicle</th>
                                                                <th class="align-content-center text-center">User</th>
                                                                <th class="align-content-center text-center">WNA Cost</th>
                                                                @foreach ($accommodationTypes as $type)
                                                                    <th class="align-content-center text-center">{{ $type }}</th>
                                                                @endforeach
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @if (!empty($group['data']) && is_array($group['data']))
                                                                @foreach ($group['data'] as $index => $priceRow)
                                                                    @if ($index !== 0) <!-- Melewati objek pertama yang hanya berisi "Price Type" -->
                                                                        <tr>
                                                                            <td class="align-content-center text-center">{{ $priceType }}</td>
                                                                            <td class="align-content-center text-center">{{ $priceRow['vehicle'] ?? '-' }}</td>
                                                                            <td class="align-content-center text-center">{{ $priceRow['user'] ?? '-' }}</td>
                                                                            <td class="align-content-center text-center">{{ number_format($priceRow['wnaCost'] ?? 0, 0, ',', '.') }} /org</td>
                                                                            @foreach ($accommodationTypes as $typeKey => $typeLabel)
                                                                                <td class="align-content-center text-center">{{ number_format($priceRow[$typeKey] ?? 0, 0, ',', '.') }} /org</td>
                                                                            @endforeach
                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td colspan="{{ 4 + count($accommodationTypes) }}" class="text-center">No price data available</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="{{ 4 + count($accommodationTypes) }}" class="text-center">No price data available</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach


        <div class="mt-4">
            {{ $packages->links() }}
        </div>
    </div>
</div>
@endsection
