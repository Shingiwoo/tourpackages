@extends('admin.admin_dashboard')
@section('admin')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1">Create Custom Package</h4>
                <p class="mb-0">Quickly calculate the price of a tour package from 1 day - 4 days, <br> according to the costs and details entered.</p>
            </div>
            <div class="d-flex align-content-center flex-wrap gap-4">
                <a href="{{ route('all.custom.package') }}" class="btn btn-primary">View All</a>
            </div>
        </div>
        <div class="row g-6">
            <div class="col-xxl-4 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Detail Custom Package</h5>
                        </div>
                    </div>
                    <form action="{{ route('store.custom.package') }}" method="POST">
                        @csrf
                        <div class="card-body" style="position: relative;">
                            <h6>1. Destinasi, Fasilitas & Kendaraan</h6>
                            <div class="row g-6">
                                <div class="col mb-4">
                                    <label class="form-label" for="destinations">Destination</label>
                                    <select id="destinations" name="destinations[]" class="select2 form-select" multiple
                                        required>
                                        @foreach ($destinations as $destination)
                                        <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="form-label" for="vehicle">Vehicle</label>
                                <select id="vehicle" name="vehicleName" class="select2 form-select" multiple required>
                                    @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->name }} | Kapasitas: {{
                                        $vehicle->capacity_min
                                        }}-{{ $vehicle->capacity_max }} Org | {{ $vehicle->regency->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row mb-4">
                                <div class="col">
                                    <label class="form-label" for="facility">Facility</label>
                                    <select id="facility" name="facilities[]" class="select2 form-select" multiple
                                        required>
                                        @foreach ($facilities as $facility)
                                        <option value="{{ $facility->id }}">{{ $facility->name }} | Type: {{
                                            $facility->type }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <hr class="my-6 mx-n4">
                            <h6>2. Detail Info</h6>
                            <div class="row g-6">
                                <div class="col mb-4">
                                    <label class="form-label" for="duration_package">Duration</label>
                                    <input type="number" class="form-control" id="duration_package" placeholder="5"
                                        max="10" name="DurationPackage" aria-label="Duration" required />
                                </div>
                                <div class="col mb-4">
                                    <label class="form-label" for="totalUser">Total User</label>
                                    <input type="number" class="form-control" id="totalUser" placeholder="5" max="999"
                                        name="totalUser" aria-label="Total User" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-4">
                                    <label class="form-label" for="otherFee">Other Fee</label>
                                    <input type="text" id="otherFee" class="form-control numeral-mask"
                                        placeholder="500000" name="otherFee" aria-label="Other Fee" required />
                                </div>
                                <div class="col mb-4">
                                    <label class="form-label" for="reservedFee">Reserved Fee</label>
                                    <input type="text" id="reservedFee" class="form-control numeral-mask2"
                                        placeholder="500000" name="reservedFee" aria-label="Reserved Fee" required />
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-6 mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="IncludeMakan"
                                            id="IncludeMakan">
                                        <label class="form-check-label" for="IncludeMakan"> Include Makan </label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="IncludeHotel"
                                            id="IncludeHotel">
                                        <label class="form-check-label" for="IncludeHotel"> Include Hotel </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="meal-row">
                                <div class="col-sm-12 col-md-6 mb-4">
                                    <label class="form-label" for="mealCost">Meal Cost</label>
                                    <input type="text" id="mealCost" class="form-control numeral-mask4"
                                        placeholder="500000" name="mealCost" aria-label="Meal Cost" required />
                                </div>
                                <div class="col-sm-12 col-md-6 mb-4">
                                    <label class="form-label" for="totalMeal">Total Meal</label>
                                    <input type="number" class="form-control" id="totalMeal" placeholder="5" max="50"
                                        name="totalMeal" aria-label="Total Meal" required />
                                </div>
                            </div>
                            <div class="row" id="hotel-row">
                                <div class="col-sm-12 col-md-4 mb-4">
                                    <label class="form-label" for="hotelPrice">Hotel</label>
                                    <input type="text" id="hotelPrice" class="form-control numeral-mask3"
                                        placeholder="500000" name="hotelPrice" aria-label="Hotel" required />
                                </div>
                                <div class="col-sm-12 col-md-4 mb-4">
                                    <label class="form-label" for="night">Night</label>
                                    <input type="number" class="form-control" id="night" placeholder="5" max="50"
                                        name="night" aria-label="Night" required />
                                </div>
                                <div class="col-sm-12 col-md-4 mb-4">
                                    <label class="form-label" for="capacityHotel">Capacity</label>
                                    <input type="number" class="form-control" id="capacityHotel" placeholder="5"
                                        max="50" name="capacityHotel" aria-label="Capacity Hotel" required />
                                </div>
                            </div>
                            <hr class="my-6 mx-n4">
                            <div class="row mt-2">
                                <div class="col text-end">
                                    <button type="submit" class="btn btn-primary">Calculate</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xxl-4 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Hasil Perhitungan :</h5>
                        </div>
                    </div>
                    <div class="card-body" style="position: relative;">
                        <div class="card-datatable table-responsive">
                            @if($prices)
                            <div class="card-datatable text-nowrap">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="fw-medium mx-2 text-center" style="width: 40%">Detail</th>
                                            <th class="fw-medium mx-2 text-center">Total Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th><i class="ti ti-car ti-lg mx-2"></i>Transport</th>
                                            <td class="text-end">Rp {{ number_format($prices['transportCost'], 0, ',',
                                                '.')
                                                }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="ti ti-parking-circle ti-lg mx-2"></i>Biaya Parkir</th>
                                            <td class="text-end">Rp {{ number_format($prices['parkingCost'], 0, ',',
                                                '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><i class="ti ti-ticket ti-lg mx-2"></i>Tiket Masuk</th>
                                            <td class="text-end">Rp {{ number_format($prices['ticketCost'], 0, ',', '.')
                                                }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><i class="ti ti-soup ti-lg mx-2"></i>Biaya Makan</th>
                                            <td class="text-end">Rp {{ number_format($prices['totalMealCost'], 0, ',',
                                                '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><i class="ti ti-building-skyscraper ti-lg mx-2"></i>Penginapan</th>
                                            <td class="text-end">Rp {{ number_format($prices['hotelCost'], 0, ',', '.')
                                                }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><i class="ti ti-devices-dollar ti-lg mx-2"></i>Biaya Lain</th>
                                            <td class="text-end">Rp {{ number_format($prices['otherFee'], 0, ',', '.')
                                                }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><i class="ti ti-clock-dollar ti-lg mx-2"></i>Biaya Jasa</th>
                                            <td class="text-end">Rp {{ number_format($prices['reservedFee'], 0, ',',
                                                '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><i class="ti ti-home-infinity ti-lg mx-2"></i>Fasilitas</th>
                                            <td class="text-end">Rp {{ number_format($prices['facilityCost'], 0, ',',
                                                '.')
                                                }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="ti ti-receipt ti-lg mx-2"></i><strong>Total Biaya</strong>
                                            </th>
                                            <td class="text-end"><strong>Rp {{ number_format($prices['totalCost'], 0,
                                                    ',',
                                                    '.') }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th><i class="ti ti-cash-register ti-lg mx-2"></i><strong>Down
                                                    Payment</strong></th>
                                            <td class="text-end"><strong>Rp {{ number_format($prices['downPayment'], 0,
                                                    ',',
                                                    '.') }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th><i class="ti ti-cash ti-lg mx-2"></i><strong>Sisa Biaya</strong></th>
                                            <td class="text-end"><strong>Rp {{ number_format($prices['remainingCosts'],
                                                    0,
                                                    ',', '.') }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-6">
                                <h6 class="text-warning">Keterangan :</h6>
                                <ul>
                                    <li class="mb-1">
                                        Jadi untuk biaya Perorang : Rp <b>{{ number_format($prices['costPerPerson'], 0,
                                            ',',
                                            '.') }}</b>
                                    </li>
                                    <li class="mb-1">
                                        Dengan minimal jumlah peserta <b>{{ $prices['participants'] }}</b> dewasa
                                    </li>
                                    <li class="mb-1">
                                        Biaya Tambahan untuk WNA : Rp <b>{{ number_format($prices['additionalCostWna'],
                                            0,
                                            ',', '.') }}</b> /orang
                                    </li>
                                    <li class="mb-1">
                                        Biaya untuk anak2 usia 3-10 tahun : Rp <b>{{ number_format($prices['childCost'],
                                            0,
                                            ',', '.') }}</b> /anak
                                    </li>
                                </ul>
                            </div>
                            <div class="row">
                                <div class="col text-start">
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#customSave">
                                        Save
                                    </button>
                                </div>
                                <div class="col text-end">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#detailCustomPack">
                                        View Detail
                                    </button>
                                </div>
                            </div>

                            @else
                            <p class="text-center">Tidak ada data custom package tersedia.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->

    <!-- Costum Pack Modal -->
    <div class="modal fade" id="detailCustomPack" tabindex="-1">
        <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
            <div class="modal-content">
                @if($prices)
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="address-title mb-2">Custom Package</h4>
                        <p class="address-subtitle">Trip Detail for {{ $prices['DurationPackage'] }} day {{
                            $prices['night'] }} night</p>
                    </div>
                    <div class="row mb-4">
                        <h5 class="text-warning mb-2">*Destinasi* :</h5>
                        <div class="col">
                            <div class="demo-inline-spacing mt-4">
                                <ol class="list-group">
                                    @forelse ($prices['destinationNames'] as $name)
                                    <li class="list-group-item">- {{ $name }}</li>
                                    @empty
                                    <li class="list-group-item">No destinations available</li>
                                    @endforelse
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <h5 class="text-warning mb-2">*Fasilitas* :</h5>
                        <div class="col">
                            <div class="demo-inline-spacing mt-4">
                                <ol class="list-group">
                                    @forelse ($prices['facilityNames'] as $name)
                                    <li class="list-group-item">- {{ $name }}</li>
                                    @empty
                                    <li class="list-group-item">No destinations available</li>
                                    @endforelse
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <p class="text-center">Tidak ada data custom package tersedia.</p>
                @endif
            </div>

        </div>
    </div>
    <!--/ Costum Pack Modal -->

    <!-- Custom Save Modal -->
    <div class="modal fade" id="customSave" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2">Save Custom Package</h4>
                        <p>Fill Detail Package form</p>
                    </div>
                    <form id="customSaveForm" class="row g-6" action="{{ route('save.custom.package') }}" method="POST">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-12">
                                <label class="form-label" for="saveCustName">Package Name</label>
                                <input type="text" id="saveCustName" name="saveCustName"
                                    class="form-control" placeholder="CUST-1D" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="saveCustAgen">Status</label>
                                <select id="saveCustAgen" name="saveCustAgen" class="select2 form-select"
                                    aria-label="Agen Name" required>
                                    @foreach ($allagens as $agen )
                                        <option value="{{ $agen->id }}">{{ $agen->username }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="regency">Location</label>
                                <select id="regency" name="regency" class="select2 form-select" required>
                                    @foreach ($regencies as $regency)
                                    <option value="{{ $regency->id }}">{{ $regency->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="text" id="saveCustType" name="saveCustType" value="custom" hidden/>
                        <input type="text" id="saveStatus" name="saveStatus" value="active" hidden/>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-3">Submit</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/ Custom Save Modal -->

    <!-- End Content -->
</div>


@endsection
