@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div
                class="row-gap-4 mb-6 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Create Custom Package</h4>
                    <p class="mb-0">Quickly calculate the price of a tour package from 1 day - 4 days, <br> according to
                        the costs and details entered.</p>
                </div>
                <div class="flex-wrap gap-4 d-flex align-content-center">
                    <a href="{{ route('all.custom.package') }}" class="btn btn-primary">View All</a>
                </div>
            </div>
            <div class="row g-6">
                <div class="col-xxl-4 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="mb-0 card-title">
                                <h5 class="m-0 me-2">Detail Custom Package</h5>
                            </div>
                        </div>
                        <form action="{{ route('store.custom.package') }}" method="POST">
                            @csrf
                            <div class="card-body" style="position: relative;">
                                <h6>1. Destinasi, Fasilitas & Kendaraan</h6>
                                <div class="row g-6">
                                    <div class="mb-4 col">
                                        <label class="form-label" for="destinations">Destination</label>
                                        <select id="destinations" name="destinations[]" class="select2 form-select" multiple
                                            required>
                                            @foreach ($destinations as $destination)
                                                <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4 row">
                                    <label class="form-label" for="vehicle">Vehicle</label>
                                    <select id="vehicle" name="vehicleName" class="select2 form-select" required>
                                        @foreach ($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}">{{ $vehicle->name }} | Kapasitas:
                                                {{ $vehicle->capacity_min }}-{{ $vehicle->capacity_max }} Org |
                                                {{ $vehicle->regency->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4 row">
                                    <div class="col">
                                        <label class="form-label" for="facility">Facility</label>
                                        <select id="facility" name="facilities[]" class="select2 form-select" multiple
                                            required>
                                            @foreach ($facilities as $facility)
                                                <option value="{{ $facility->id }}">{{ $facility->name }} | Type:
                                                    {{ $facility->type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <hr class="my-6 mx-n4">
                                <h6>2. Detail Info</h6>
                                <div class="row g-6">
                                    <div class="mb-4 col">
                                        <label class="form-label" for="duration_package">Duration (Days)</label>
                                        <input type="number" class="form-control" id="duration_package" placeholder="5"
                                            max="10" name="DurationPackage" aria-label="Duration" required />
                                    </div>
                                    <div class="mb-4 col">
                                        <label class="form-label" for="totalUser">Total Participants</label>
                                        <input type="number" class="form-control" id="totalUser" placeholder="5"
                                            max="999" name="totalUser" aria-label="Total User" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-4 col">
                                        <label class="form-label" for="otherFee">Other Fee</label>
                                        <input type="text" id="otherFee" class="form-control currency-input"
                                            placeholder="500000" name="otherFee" aria-label="Other Fee" required />
                                    </div>
                                    <div class="mb-4 col">
                                        <label class="form-label" for="reservedFee">Reserved Fee</label>
                                        <input type="text" id="reservedFee" class="form-control currency-input"
                                            placeholder="500000" name="reservedFee" aria-label="Reserved Fee" required />
                                    </div>
                                </div>
                                <div class="mt-2 row">
                                    <div class="mb-4 col-sm-6 col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="IncludeMakan"
                                                id="IncludeMakan">
                                            <label class="form-check-label" for="IncludeMakan"> Include Meal </label>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-sm-6 col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="IncludeHotel"
                                                id="IncludeHotel">
                                            <label class="form-check-label" for="IncludeHotel">Manual Hotel
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-sm-6 col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="SelectHotel"
                                                id="SelectHotel">
                                            <label class="form-check-label" for="SelectHotel">Select Hotel
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="meal-row">
                                    <div class="mb-4 col-sm-12 col-md-6">
                                        <label class="form-label" for="mealCost">Meal Cost Per Person</label>
                                        <input type="text" id="mealCost" class="form-control currency-input"
                                            placeholder="50000" name="mealCost" aria-label="Meal Cost" required />
                                    </div>
                                    <div class="mb-4 col-sm-12 col-md-6">
                                        <label class="form-label" for="totalMeal">Total Meals Per Day</label>
                                        <input type="number" class="form-control" id="totalMeal" placeholder="3"
                                            max="50" name="totalMeal" aria-label="Total Meal" required />
                                    </div>
                                </div>

                                {{-- Manual Hotel Input --}}
                                <div class="row" id="manual-hotel-row">
                                    <div class="mb-4 col-sm-12 col-md-6">
                                        <label class="form-label" for="hotelPrice">Hotel Price Per Room</label>
                                        <input type="text" id="hotelPrice" class="form-control currency-input"
                                            placeholder="500000" name="hotelPrice" aria-label="Hotel" required />
                                    </div>
                                    <div class="mb-4 col-sm-12 col-md-6">
                                        <label class="form-label" for="night">Number of Nights</label>
                                        <input type="number" class="form-control" id="night" placeholder="1"
                                            min="0" max="50" name="night" aria-label="Night" required />
                                    </div>
                                    <div class="mb-4 col-sm-12 col-md-6">
                                        <label class="form-label" for="capacityHotel">Room Capacity</label>
                                        <input type="number" class="form-control" id="capacityHotel" placeholder="2"
                                            min="1" max="50" name="capacityHotel"
                                            aria-label="Capacity Hotel" required />
                                    </div>
                                    <div class="mb-4 col-sm-12 col-md-6">
                                        <label class="form-label" for="extraBedPrice">Extra Bed Price Per Night</label>
                                        <input type="text" id="extraBedPrice" class="form-control currency-input"
                                            placeholder="150000" name="extraBedPrice" aria-label="Extra Bed" required />
                                    </div>
                                </div>

                                {{-- Advanced Hotel Selection --}}
                                <div class="row" id="advanced-hotel-row">
                                    <div class="mb-4 col-12">
                                        <label class="form-label" for="selectedHotels">Select Hotels</label>
                                        <select id="selectedHotels" name="selectedHotels[]" class="select2 form-select"
                                            multiple>
                                            @foreach ($hotels as $hotel)
                                                <option value="{{ $hotel->id }}">{{ $hotel->name }}
                                                    ({{ $hotel->type }} - Cap: {{ $hotel->capacity }} - Price: Rp
                                                    {{ number_format($hotel->price, 0, ',', '.') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Untuk Homestay, Villa, Cottage, maksimal penambahan
                                            extrabed 2 unit untuk 2 orang.</small>
                                    </div>
                                    <div class="mb-4 col-sm-12 col-md-6">
                                        <label class="form-label" for="advancedExtraBedPrice">Extra Bed Price Per
                                            Night</label>
                                        <input type="text" id="advancedExtraBedPrice"
                                            class="form-control currency-input" placeholder="150000" name="advancedExtraBedPrice"
                                            aria-label="Advanced Extra Bed" />
                                        <small class="text-muted">Ini akan digunakan sebagai harga extrabed default jika
                                            tidak ada harga extrabed spesifik dari hotel yang dipilih.</small>
                                    </div>
                                    <div class="mb-4 col-sm-12 col-md-6">
                                        <label class="form-label" for="nightAdvanced">Number of Nights</label>
                                        <input type="number" class="form-control" id="nightAdvanced" placeholder="1"
                                            min="0" max="50" name="nightAdvanced" aria-label="Night Advanced" />
                                    </div>
                                </div>

                                <hr class="my-6 mx-n4">
                                <div class="mt-2 row">
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
                            <div class="mb-0 card-title">
                                <h5 class="m-0 me-2">Hasil Perhitungan :</h5>
                            </div>
                        </div>
                        <div class="card-body" style="position: relative;">
                            <div class="card-datatable table-responsive">
                                @if ($prices)
                                    <div class="card-datatable text-nowrap">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="mx-2 text-center fw-medium" style="width: 40%">Detail</th>
                                                    <th class="mx-2 text-center fw-medium">Total Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th><i class="mx-2 ti ti-car ti-lg"></i>Transport Cost</th>
                                                    <td class="text-end">Rp
                                                        {{ number_format($prices['transportCost'], 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><i class="mx-2 ti ti-parking-circle ti-lg"></i>Parking Cost</th>
                                                    <td class="text-end">Rp
                                                        {{ number_format($prices['parkingCost'], 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><i class="mx-2 ti ti-ticket ti-lg"></i>Ticket Cost</th>
                                                    <td class="text-end">Rp
                                                        {{ number_format($prices['ticketCost'], 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><i class="mx-2 ti ti-soup ti-lg"></i>Meal Cost</th>
                                                    <td class="text-end">Rp
                                                        {{ number_format($prices['totalMealCost'], 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><i class="mx-2 ti ti-building-skyscraper ti-lg"></i>Accommodation
                                                        Cost</th>
                                                    <td class="text-end">Rp
                                                        {{ number_format($prices['hotelCost'], 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><i class="mx-2 ti ti-bed ti-lg"></i>Extra Bed Cost</th>
                                                    <td class="text-end">Rp
                                                        {{ number_format($prices['extraBedCost'], 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><i class="mx-2 ti ti-devices-dollar ti-lg"></i>Other Fee</th>
                                                    <td class="text-end">Rp
                                                        {{ number_format($prices['otherFee'], 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><i class="mx-2 ti ti-clock-dollar ti-lg"></i>Reserved Fee</th>
                                                    <td class="text-end">Rp
                                                        {{ number_format($prices['reservedFee'], 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><i class="mx-2 ti ti-home-infinity ti-lg"></i>Facility Cost</th>
                                                    <td class="text-end">Rp
                                                        {{ number_format($prices['facilityCost'], 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><i class="mx-2 ti ti-receipt ti-lg"></i><strong>Total Cost</strong>
                                                    </th>
                                                    <td class="text-end"><strong>Rp
                                                            {{ number_format($prices['totalCost'], 0, ',', '.') }}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><i class="mx-2 ti ti-cash-register ti-lg"></i><strong>Down
                                                            Payment (30%)</strong></th>
                                                    <td class="text-end"><strong>Rp
                                                            {{ number_format($prices['downPayment'], 0, ',', '.') }}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><i class="mx-2 ti ti-cash ti-lg"></i><strong>Remaining
                                                            Cost</strong></th>
                                                    <td class="text-end"><strong>Rp
                                                            {{ number_format($prices['remainingCosts'], 0, ',', '.') }}</strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-6">
                                        <h6 class="text-warning">Notes :</h6>
                                        <ul>
                                            <li class="mb-1">
                                                Cost Per Person : Rp
                                                <b>{{ number_format($prices['costPerPerson'], 0, ',', '.') }}</b>
                                            </li>
                                            <li class="mb-1">
                                                With minimum number of participants: <b>{{ $prices['participants'] }}</b>
                                                adults
                                            </li>
                                            <li class="mb-1">
                                                Additional Cost for Foreigners (WNA) : Rp
                                                <b>{{ number_format($prices['additionalCostWna'], 0, ',', '.') }}</b>
                                                /person
                                            </li>
                                            <li class="mb-1">
                                                Cost for children aged 3-10 years : Rp
                                                <b>{{ number_format($prices['childCost'], 0, ',', '.') }}</b>
                                                /child
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
                                    <p class="text-center">No custom package data available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="detailCustomPack" tabindex="-1">
            <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
                <div class="modal-content">
                    @if ($prices)
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                            <div class="mb-6 text-center">
                                <h4 class="mb-2 address-title">Custom Package</h4>
                                <p class="address-subtitle">Trip Detail for {{ $prices['DurationPackage'] }}
                                    day{{ $prices['DurationPackage'] > 1 ? 's' : '' }} {{ $prices['night'] }}
                                    night{{ $prices['night'] > 1 ? 's' : '' }}</p>
                            </div>
                            <div class="mb-4 row">
                                <h5 class="mb-2 text-warning">*Destinations* :</h5>
                                <div class="col">
                                    <div class="mt-4 demo-inline-spacing">
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
                            <div class="mb-4 row">
                                <h5 class="mb-2 text-warning">*Facilities* :</h5>
                                <div class="col">
                                    <div class="mt-4 demo-inline-spacing">
                                        <ol class="list-group">
                                            @forelse ($prices['facilityNames'] as $name)
                                                <li class="list-group-item">- {{ $name }}</li>
                                            @empty
                                                <li class="list-group-item">No facilities available</li>
                                            @endforelse
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 row">
                                <h5 class="mb-2 text-warning">*Accommodations* :</h5>
                                <div class="col">
                                    <div class="mt-4 demo-inline-spacing">
                                        <ol class="list-group">
                                            @forelse ($prices['hotelNames'] ?? [] as $name)
                                                <li class="list-group-item">- {{ $name }}</li>
                                            @empty
                                                <li class="list-group-item">No accommodations selected or manual input.
                                                </li>
                                            @endforelse
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-center">No custom package data available.</p>
                    @endif
                </div>

            </div>
        </div>
        <div class="modal fade" id="customSave" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="mb-6 text-center">
                            <h4 class="mb-2">Save Custom Package</h4>
                            <p>Fill Detail Package form</p>
                        </div>
                        <form id="customSaveForm" class="row g-6" action="{{ route('save.custom.package') }}"
                            method="POST">
                            @csrf
                            <div class="mb-2 row">
                                <div class="col-12">
                                    <label class="form-label" for="saveCustName">Package Name</label>
                                    <input type="text" id="saveCustName" name="saveCustName" class="form-control"
                                        placeholder="CUST-1D" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="saveCustAgen">Agent</label>
                                    <select id="saveCustAgen" name="saveCustAgen" class="select2 form-select"
                                        aria-label="Agen Name" required>
                                        @foreach ($allagens as $agen)
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
                            <input type="text" id="saveCustType" name="saveCustType" value="custom" hidden />
                            <input type="text" id="saveStatus" name="saveStatus" value="active" hidden />
                            <div class="text-center col-12">
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
    </div>

    <script>
        // Form Custom Page
        $(document).ready(function() {
            // --- Handler untuk checkbox IncludeMakan ---
            $('#IncludeMakan').change(function() {
                var isChecked = $(this).is(':checked');
                var mealCostRow = $('#meal-row');
                var mealCostInput = $('#mealCost');
                var totalMealInput = $('#totalMeal');

                if (isChecked) {
                    mealCostRow.show();
                    mealCostInput.val('').prop('required', true).prop('disabled',
                    false); // Set disabled ke false
                    totalMealInput.val('').prop('required', true).prop('disabled',
                    false); // Set disabled ke false
                } else {
                    mealCostRow.hide();
                    mealCostInput.val(0).prop('required', false).prop('disabled',
                    true); // Set disabled ke true
                    totalMealInput.val(1).prop('required', false).prop('disabled',
                    true); // Set disabled ke true
                }
            }).trigger('change'); // Inisialisasi saat load

            // --- Handler untuk checkbox Manual Hotel ---
            $('#IncludeHotel').change(function() {
                var isChecked = $(this).is(':checked');
                var manualHotelRow = $('#manual-hotel-row');
                var selectHotelCheckbox = $('#SelectHotel');

                // Kumpulan input di form manual hotel
                var manualHotelInputs = $('#hotelPrice, #night, #capacityHotel, #extraBedPrice');

                if (isChecked) {
                    manualHotelRow.show();
                    // Uncheck select hotel if manual is checked
                    selectHotelCheckbox.prop('checked', false).trigger('change');

                    // Set required dan DISABLED ke false untuk manual inputs
                    $('#hotelPrice').prop('required', true).val('').prop('disabled', false);
                    $('#night').prop('required', true).val('0').prop('disabled', false);
                    $('#capacityHotel').prop('required', true).val('').prop('disabled', false);
                    $('#extraBedPrice').prop('required', true).val('').prop('disabled', false);
                } else {
                    manualHotelRow.hide();
                    // Reset nilai dan SET DISABLED ke true untuk manual inputs
                    $('#hotelPrice').val(0).prop('required', false).prop('disabled', true);
                    $('#night').val(0).prop('required', false).prop('disabled', true);
                    $('#capacityHotel').val(1).prop('required', false).prop('disabled', true);
                    $('#extraBedPrice').val(0).prop('required', false).prop('disabled', true);
                }
            });

            // --- Handler untuk checkbox Select Hotel ---
            $('#SelectHotel').change(function() {
                var isChecked = $(this).is(':checked');
                var advancedHotelRow = $('#advanced-hotel-row');
                var manualHotelCheckbox = $('#IncludeHotel');

                // Kumpulan input di form advanced hotel
                var advancedHotelInputs = $('#selectedHotels, #advancedExtraBedPrice, #nightAdvanced');

                if (isChecked) {
                    advancedHotelRow.show();
                    // Uncheck manual hotel if select is checked
                    manualHotelCheckbox.prop('checked', false).trigger('change');

                    // Set required dan DISABLED ke false untuk advanced inputs
                    $('#selectedHotels').prop('required', true).prop('disabled', false);
                    $('#advancedExtraBedPrice').prop('required', true).val('').prop('disabled', false);
                    $('#nightAdvanced').prop('required', true).val('0').prop('disabled', false);
                } else {
                    advancedHotelRow.hide();
                    // Reset nilai dan SET DISABLED ke true untuk advanced inputs
                    $('#selectedHotels').val(null).trigger('change').prop('required', false).prop(
                        'disabled', true);
                    $('#advancedExtraBedPrice').val(0).prop('required', false).prop('disabled', true);
                    $('#nightAdvanced').val(0).prop('required', false).prop('disabled', true);
                }
            });

            // Initialize state - hide both hotel rows initially AND disable their inputs
            $('#manual-hotel-row').hide();
            $('#advanced-hotel-row').hide();

            // Penting: Saat load, set semua input hotel (manual dan advanced) ke disabled
            // Ini memastikan hanya input yang diaktifkan oleh checkbox yang akan terkirim.
            $('#hotelPrice, #night, #capacityHotel, #extraBedPrice').prop('disabled', true);
            $('#selectedHotels, #advancedExtraBedPrice, #nightAdvanced').prop('disabled', true);

            // Also make sure select2 is initialized properly
            $('.select2').select2();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const currencyInputs = document.querySelectorAll('.currency-input');

            currencyInputs.forEach(input => {
                // Function to format the number as Indonesian Rupiah currency
                function formatCurrency(value) {
                    // Remove non-numeric characters first
                    let number = parseInt(value.replace(/\D/g, ''), 10);

                    if (isNaN(number)) {
                        return '';
                    }

                    // Format as Rupiah
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number);
                }

                // Set initial format if there's a value
                if (input.value) {
                    input.value = formatCurrency(input.value);
                }

                // Add event listener for when the input loses focus (blur)
                input.addEventListener('blur', function() {
                    this.value = formatCurrency(this.value);
                });

                // Add event listener for when the input gains focus (focus)
                input.addEventListener('focus', function() {
                    // Remove 'Rp' and dots for easier editing
                    this.value = this.value.replace(/Rp|\./g, '').trim();
                });

                // Add event listener for input changes (as user types)
                input.addEventListener('input', function() {
                    // Remove non-numeric characters except for commas and dots
                    this.value = this.value.replace(/[^0-9,\.]/g, '');
                });
            });
        });
    </script>
@endsection
