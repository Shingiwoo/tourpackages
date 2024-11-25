@extends('admin.admin_dashboard')
@section('admin')


<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <form action="{{ route('destination.update') }}" method="POST">
        @csrf

        <input type="hidden" name="id"  value="{{ $dest->id }}"/>

        <div class="app-ecommerce">
            <!-- Add Product -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Update Destination</h4>
                    <p class="mb-0">Place name, to create a tour package</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <button type="submit" class="btn btn-info">Update</button>
                </div>
            </div>

            <div class="row">
                <!-- First column-->
                <div class="col-12 col-lg-8">
                    <!-- Product Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Destination Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="name_destination">Name</label>
                                    <input type="text" class="form-control" id="name_destination"
                                        placeholder="Name Destination" name="NameDestination"
                                        aria-label="Name Destination" required value="{{ $dest->name }}"/>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="city_district">City / District</label>
                                    <select required id="city_district" name="cityOrDistrict_id" class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select City / District</option>
                                        @foreach($regencies as $regency)
                                            <option value="{{ $regency->id }}" {{ $dest->regency_id == $regency->id ? 'selected' : '' }}>
                                                {{ $regency->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label" for="status_destination">Status</label>
                                    <select required id="status_destination" name="statusDestination" class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select Status</option>
                                        <option value="1" {{ $dest->status == 1 ? 'selected' : '' }}>ACTIVE</option>
                                        <option value="0" {{ $dest->status == 0 ? 'selected' : '' }}>INACTIVE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="car_parking_fees">Car Parking Fees</label>
                                    <input type="text" class="form-control numeral-mask3" id="car_parking_fees"
                                        placeholder="5000" name="carParkingFees" aria-label="Car Parking Fees"
                                        required value="{{ $dest->parking_city_car }}"/>
                                </div>
                                <div class="col">
                                    <label class="form-label" for="minibus_parking_fees">Minibus Parking Fees</label>
                                    <input type="text" class="form-control numeral-mask4" id="minibus_parking_fees"
                                        placeholder="25000" name="minibusParkingFees" aria-label="Minibus Parking Fees"
                                        required value="{{ $dest->parking_mini_bus }}"/>
                                </div>
                                <div class="col">
                                    <label class="form-label" for="bus_parking_fees">Bus Parking Fees</label>
                                    <input type="text" class="form-control numeral-mask5" id="bus_parking_fees"
                                        placeholder="100000" name="busParkingFees" aria-label="Bus Parking Fees"
                                        required value="{{ $dest->parking_bus }}"/>
                                </div>

                            </div>
                            <!-- Description -->
                            <div>
                                <label class="mb-1">Information (Optional)</label>
                                <textarea class="form-control" name="information" id="information" rows="2">{{ $dest->ket }}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /Product Information -->
                </div>
                <div class="col-12 col-lg-4">
                    <!-- Pricing Card -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Pricing</h5>
                        </div>
                        <div class="card-body">
                            <!-- Base Price -->
                            <div class="mb-6">
                                <label class="form-label" for="tiket_price_wni">Ticket Price WNI</label>
                                <input type="text" class="form-control numeral-mask" id="tiket_price_wni"
                                    placeholder="500000" name="priceWni" aria-label="Ticket Price WNI" required value="{{ $dest->price_wni }}"/>
                            </div>
                            <!-- Discounted Price -->
                            <div class="mb-6">
                                <label class="form-label" for="tiket_price_wna">Ticket Price WNA</label>
                                <input type="text" class="form-control numeral-mask2" id="tiket_price_wna"
                                    placeholder="600000" name="priceWna" aria-label="Ticket Price WNA" required value="{{ $dest->price_wna }}"/>
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="price_type">Price Type</label>
                                <select required id="price_type" name="priceType" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select Type</option>
                                    <option value="per_person" {{ $dest->price_type == 'per_person' ? 'selected' : '' }}>Per Person</option>
                                    <option value="flat" {{ $dest->price_type == 'flat' ? 'selected' : '' }}>Flat</option>
                                </select>
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="max_users">Max User</label>
                                <input type="number" class="form-control" id="max_users" placeholder="1" min="1"
                                    max="45" name="maxUser" aria-label="Max User" required value="{{ $dest->max_participants }}"/>
                            </div>
                        </div>
                    </div>
                    <!-- /Pricing Card -->
                </div>
            </div>
        </div>
    </form>
</div>
<!-- / Content -->

@endsection
