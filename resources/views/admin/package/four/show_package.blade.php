@extends('admin.admin_dashboard')
@section('admin')

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row invoice-add">
        <!-- Data Package -->
        <div class="col-lg-9 col-12 mb-lg-0 mb-6">
            <div class="card invoice-preview-card p-sm-12 p-6">
                <div class="card-header pb-0 d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="mb-1">{{ $package->name_package }}</h5>
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
                                            @forelse ($package->destinations as $desti)
                                            <li class="list-group-item">{{ $desti->name }}</li>
                                            @empty
                                            <li class="list-group-item">No destinations available</li>
                                            @endforelse
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2 mt-4">
                                <h6 class="text-danger">Information :</h6>
                                <small class="fw-medium">{{ strip_tags($package->information) }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="border rounded p-5 mt-5">
                        <div class="row gap-4 gap-sm-0">
                            <div class="col-12 col-sm-6">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="badge rounded bg-label-info p-1"><i class="ti ti-chart-pie-2 ti-sm"></i></div>
                                    <h6 class="mb-0 fw-normal">Location</h6>
                                </div>
                                <h6 class="my-3 text-uppercase">{{ $package->regency->name ?? "No Data" }}</h6>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="badge rounded bg-label-danger p-1"><i class="ti ti-brand-paypal ti-sm"></i></div>
                                    <h6 class="mb-0 fw-normal">Status</h6>
                                </div>
                                <h6 class="my-3 text-uppercase">
                                    @if ($package->status)
                                    Active
                                    @else
                                    Inactive
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>

                    <div class="accordion mt-6" id="accordionWithIcon">
                        <div class="accordion-item card">
                            <h2 class="accordion-header d-flex align-items-center">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
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
                                                <thead>
                                                    <tr>
                                                        <th class="align-content-center text-center">Vehicle</th>
                                                        <th class="align-content-center text-center">User</th>
                                                        <th class="align-content-center text-center">No Accomodation</th>
                                                        <th class="align-content-center text-center">Guesthouse</th>
                                                        <th class="align-content-center text-center">Homestay</th>
                                                        <th class="align-content-center text-center">TwoStar</th>
                                                        <th class="align-content-center text-center">ThreeStar</th>
                                                        <th class="align-content-center text-center">FourStar</th>
                                                        <th class="align-content-center text-center">FiveStar</th>
                                                        <th class="align-content-center text-center">Wna Cost</th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    // Asumsikan $package->prices->price_data sudah berisi JSON yang valid
                                                    $prices = json_decode($package->prices->price_data, true);
                                                @endphp
                                                <tbody>
                                                    @if (count($prices) > 0)
                                                        @foreach ($prices as $priceRow)
                                                        <tr>
                                                            <td class="align-content-center text-center">{{ $priceRow['vehicle'] }}</td>
                                                            <td class="align-content-center text-center">{{ $priceRow['user'] }}</td>
                                                            <td class="align-content-center text-center">{{ number_format($priceRow['WithoutAccomodation'], 0, ',', '.') }} /orang</td>
                                                            <td class="align-content-center text-center">{{ number_format($priceRow['Guesthouse'], 0, ',', '.') }} /orang</td>
                                                            <td class="align-content-center text-center">{{ number_format($priceRow['Homestay'], 0, ',', '.') }} /orang</td>
                                                            <td class="align-content-center text-center">{{ number_format($priceRow['TwoStar'], 0, ',', '.') }} /orang</td>
                                                            <td>{{ number_format($priceRow['ThreeStar'], 0, ',', '.') }} /orang</td>
                                                            <td class="align-content-center text-center">{{ number_format($priceRow['FourStar'], 0, ',', '.') }} /orang</td>
                                                            <td class="align-content-center text-center">{{ number_format($priceRow['FiveStar'], 0, ',', '.') }} /orang</td>
                                                            <td class="align-content-center text-center">{{ number_format($priceRow['wnaCost'], 0, ',', '.') }} /orang</td>
                                                        </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="3" class="text-center">No price data available</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
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
        <!-- /Data Package  -->

        <!-- Actions -->
        <div class="col-lg-3 col-12 invoice-actions">
            <div class="card mb-6">
                <div class="card-body">
                    <a href="{{ route('all.fourday.packages') }}" class="btn btn-label-primary d-grid w-100 mb-4">Back</a>
                </div>
            </div>
        </div>
        <!-- /Actions -->
    </div>
</div>

@endsection
