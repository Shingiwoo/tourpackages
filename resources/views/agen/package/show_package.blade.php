@extends('agen.agen_dashboard')
@section('agen')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Data Package -->
        <div class="col-lg-9 col-12 mb-lg-0 mb-6">
            @if($package->type == 'custom')
            <div  class="card p-4">
                <div class="text-center mb-6">
                    <h5 class="address-title">Custom Package :</h5>
                    <h4 class="address-title mb-2"><span class="text-primary">{{ $package->name_package }}</span></h4>
                    <p class="address-subtitle">Trip Detail for <span class="text-primary">{{ $package->duration }}</span> day(s) <span class="text-primary">{{ $package->night }}</span> night(s)</p>
                </div>
                <div class="row mb-4">
                    <h5 class="text-warning mb-2">*Destinasi* :</h5>
                    <div class="col">
                        <div class="demo-inline-spacing">
                            <ol class="list-group">
                                @foreach ( $package->destinations as $nameDestination )
                                    <li class="list-group-item list-group-item-action waves-effect waves-light">- {{ $nameDestination }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <h5 class="text-warning mb-2">*Fasilitas* :</h5>
                    <div class="col">
                        <div class="demo-inline-spacing mt-4">
                            <ol class="list-group">
                                @foreach ( $package->facilities as $nameFacility )
                                    <li class="list-group-item list-group-item-action waves-effect waves-light">- {{ $nameFacility }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card p-4">
                <div class="card-header pb-0 d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h4 class="mb-1 text-uppercase">{{ $package->name_package }}</h4>
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
                                            <li class="list-group-item list-group-item-action waves-effect waves-light">
                                                {{ $desti->name }}</li>
                                            @empty
                                            <li class="list-group-item list-group-item-action waves-effect waves-light">
                                                No destinations available</li>
                                            @endforelse
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2 mt-4">
                                <h6 class="text-warning">Facility :</h6>
                                <div class="demo-inline-spacing mt-4">
                                    <ol class="list-group">
                                        @forelse ($package->facilities as $facility)
                                        <li class="list-group-item list-group-item-action waves-effect waves-light">{{
                                            $facility->name }}</li>
                                        @empty
                                        <li class="list-group-item list-group-item-action waves-effect waves-light">No
                                            facilities available</li>
                                        @endforelse
                                    </ol>
                                </div>
                            </div>
                            <div class="mb-2 mt-4">
                                <h6 class="text-warning">Information :</h6>
                                <p class="fw-medium">{{ strip_tags($package->information) }}</p>
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
                                <h6 class="my-3 text-uppercase">{{ $package->regency->name ?? "No Data" }}</h6>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="badge rounded bg-label-danger p-1"><i
                                            class="ti ti-brand-paypal ti-sm"></i></div>
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
                </div>
            </div>
            @endif

            <!-- Price List Package  -->
            @if($package->type == 'custom')
            <div class="card p-4 mt-8">
                <div class="card-datatable table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="fw-medium mx-2 text-center" style="width: 40%">Detail</th>
                                <th class="fw-medium mx-2 text-center">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th><i class="ti ti-receipt ti-lg mx-2"></i><strong>Biaya
                                        Perorang</strong></th>
                                <td class="text-end perperso-cost">Rp {{ number_format($package->costPerPerson, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th><i class="ti ti-user ti-lg mx-2"></i><strong>Total User</strong>
                                </th>
                                <td class="text-end total-user">{{ $package->participants }} orang</td>
                            </tr>
                            <tr>
                                <th><i class="ti ti-receipt ti-lg mx-2"></i><strong>Total Biaya</strong>
                                </th>
                                <td class="text-end total-cost">Rp {{ number_format($package->totalCost, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th><i class="ti ti-cash-register ti-lg mx-2"></i><strong>Down
                                        Payment</strong></th>
                                <td class="text-end down-payment">Rp {{ number_format($package->downPayment, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th><i class="ti ti-cash ti-lg mx-2"></i><strong>Sisa Biaya</strong>
                                </th>
                                <td class="text-end remaining-costs">Rp {{ number_format($package->remainingCosts, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row mt-4">
                        <h6 class="text-warning mb-2">*Keterangan* : </h6>
                        <ul>
                            <li class="mb-2">Biaya Anak-anak: <strong class="child-cost">Rp {{ number_format($package->childCost, 0, ',', '.') }}</strong>
                                <br><span class="st-italic fs-6 text-info">Dengan usia 4 - 10 tahun, 11
                                    tahun keatas biaya penuh</span></li>
                            <li>Biaya Tambahan WNA: <strong class="additional-cost-wna">Rp
                                {{ number_format($package->additionalCostWna, 0, ',', '.') }}</strong><br><span class="fst-italic fs-6 text-info">Untuk WNA
                                    dikenakan biaya tambahan /orang</span></li>
                        </ul>
                    </div>
                </div>
            </div>
            @else
                <div class="accordion mt-4" id="accordionExample">
                    <div class="accordion-item card text-warning">
                        <h2 class="accordion-header d-flex align-items-center">
                            <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                data-bs-target="#accordionWithIcon-2" aria-expanded="false">
                                <h5 class="text-warning text-uppercase"><i class="me-2 mb-1 ti ti-receipt-2"></i> Price List
                                </h5>
                            </button>
                        </h2>
                        <div id="accordionWithIcon-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <div class="mt-2">
                                    <div class="table-responsive text-nowrap">
                                        @if ($package->type == 'oneday')
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="align-content-center text-center text-warning">Vehicle</th>
                                                    <th class="align-content-center text-center text-warning">User</th>
                                                    <th class="align-content-center text-center text-warning">Price</th>
                                                    <th class="align-content-center text-center text-warning">Wna Cost</th>
                                                </tr>
                                            </thead>
                                            @php
                                            // Asumsikan $package->prices->price_data sudah berisi JSON yang valid
                                            $prices = json_decode($package->prices->price_data, true);
                                            @endphp

                                            <tbody>
                                                @if (count($prices) > 0)
                                                @foreach ($prices as $price)
                                                <tr>
                                                    <td class="align-content-center text-center text-info">{{
                                                        $price['vehicle'] }}</td>
                                                    <td class="align-content-center text-center text-warning">{{
                                                        $price['user'] }}</td>
                                                    <td class="align-content-center text-center text-info">Rp {{
                                                        number_format($price['price'], 0, ',', '.') }} /orang</td>
                                                    <td class="align-content-center text-center text-info">Rp {{
                                                        number_format($price['wnaCost'], 0, ',', '.') }} /orang</td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="3" class="text-center">No price data available</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                        @else
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="align-content-center text-center">Vehicle</th>
                                                    <th class="align-content-center text-center">User</th>
                                                    <th class="align-content-center text-center">Wna Cost</th>
                                                    @php
                                                    // Decode the JSON data
                                                    $prices = json_decode($package->prices->price_data, true);

                                                    // Extract accommodation types dynamically
                                                    $accommodationTypes = [];
                                                    if (is_array($prices) && count($prices) > 0) {
                                                    $firstRow = $prices[0];
                                                    $accommodationTypes = array_keys(array_filter($firstRow, function ($key)
                                                    {
                                                    return !in_array($key, ['vehicle', 'user', 'wnaCost']);
                                                    }, ARRAY_FILTER_USE_KEY));
                                                    }
                                                    @endphp
                                                    @foreach ($accommodationTypes as $type)
                                                    <th class="align-content-center text-center">{{
                                                        ucwords(str_replace(['WithoutAccomodation', 'Guesthouse',
                                                        'Homestay', 'TwoStar', 'ThreeStar', 'FourStar', 'FiveStar'],
                                                        ['Without Accommodation', 'Guesthouse', 'Homestay', 'Two Star',
                                                        'Three Star', 'Four Star', 'Five Star'], $type)) }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (is_array($prices) && count($prices) > 0)
                                                @foreach ($prices as $priceRow)
                                                <tr>
                                                    <td class="align-content-center text-center">{{ $priceRow['vehicle'] }}
                                                    </td>
                                                    <td class="align-content-center text-center">{{ $priceRow['user'] }}
                                                    </td>
                                                    <td class="align-content-center text-center">{{
                                                        number_format($priceRow['wnaCost'], 0, ',', '.') }} /org</td>
                                                    @foreach ($accommodationTypes as $type)
                                                    <td class="align-content-center text-center">{{
                                                        number_format($priceRow[$type] ?? 0, 0, ',', '.') }} /org</td>
                                                    @endforeach
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="{{ 3 + count($accommodationTypes) }}" class="text-center">
                                                        No price data available</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!-- /Data Package  -->

        <!-- Actions -->
        <div class="col-lg-3 col-12">
            <div class="card mb-6">
                <div class="card-body">
                    <a href="{{ route('agen.all.package') }}" class="btn btn-label-primary w-100 mb-4 me-2"><i
                            class="ti ti-arrow-big-left me-2"></i> Back</a>
                    @if (Auth::user()->can('booking.add'))
                    <a href="javascript:void(0)" class="btn btn-label-success w-100 mb-4 me-2" data-bs-toggle="modal"
                        data-id="{{ $package->id }}" data-type="{{ $package->type }}" data-bs-target="#bookingModal"> <i
                            class="ti ti-shopping-cart-plus"></i> Booking</a>
                    @endif
                </div>
            </div>
        </div>
        <!-- /Actions -->
    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="mb-2">Booking Information</h4>
                </div>
                <form id="bookingModalForm" class="row g-6" method="POST" action="{{ route('booking.store') }}">
                    @csrf
                    <div class="row mb-4">
                        <input type="hidden" name="package_id" id="package_Id">
                        <div class="col-12 mb-4">
                            <label class="form-label" for="modalClientName">Client Name</label>
                            <input type="text" id="modalClientName" name="modalClientName" class="form-control"
                                placeholder="johndoe007" required />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col mb-6">
                            <label class="form-label" for="modalTotalUser">Total User</label>
                            <input type="number" id="modalTotalUser" name="modalTotalUser" class="form-control"
                                placeholder="12" required />
                        </div>
                        <div class="col mb-4">
                            <label for="modalShow_packageType" class="form-label">Package Type</label>
                            <input type="text" id="modalShow_packageType" name="modalPackageType" value=""
                                class="form-control" placeholder="oneday" required readonly />
                        </div>
                        <div class="col mb-4" id="hotel_type_container" style="display: none;">
                            <label for="modal_hotelType" class="form-label">Hotel Type</label>
                            <select id="modal_hotelType" name="modalHotelType" class="select2 form-select"
                                data-allow-clear="true" required>
                                <option value="">Select Type</option>
                                <option value="TwoStar">Bintang 2</option>
                                <option value="ThreeStar">Bintang 3</option>
                                <option value="FourStar">Bintang 4</option>
                                <option value="FiveStar">Bintang 5</option>
                                <option value="Villa">Villa</option>
                                <option value="Homestay">Homestay</option>
                                <option value="Cottage">Cottage</option>
                                <option value="Cabin">Cabin</option>
                                <option value="Guesthouse">Guesthouse</option>
                                <option value="WithoutAccomodation">Without Accommodation</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col mb-4">
                            <label for="bs-datepicker-autoclose" class="form-label">Start Date</label>
                            <input type="text" id="bs-datepicker-autoclose" placeholder="MM/DD/YYYY"
                                class="form-control" name="modalStartDate" required />
                        </div>
                        <div class="col mb-4">
                            <label for="bs-datepicker-autoclose2" class="form-label">End Date</label>
                            <input type="text" id="bs-datepicker-autoclose2" placeholder="MM/DD/YYYY"
                                class="form-control" name="modalEndDate" required />
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-3">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Booking Modal -->

@endsection
