@extends('admin.admin_dashboard')
@section('admin')


<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <form id="mydata" action="{{ route('vehicle.store') }}" method="POST">
        @csrf
        <div class="app-ecommerce">
            <!-- Add Product -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Add a new Vehicle</h4>
                    <p class="mb-0">Place name, to create a tour package</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <button type="submit" class="btn btn-primary">Publish</button>
                </div>
            </div>

            <div class="row">
                <!-- First column-->
                <div class="col-12 col-lg-8">
                    <!-- Product Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Vehicle Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="name_vehicle">Name</label>
                                    <input type="text" class="form-control" id="name_vehicle" placeholder="Name Vehicle" name="NameVehicle" aria-label="Name Vehicle" required />
                                </div>
                                <div class="col">
                                    <label class="form-label" for="car_type">Car Type</label>
                                    <select required id="car_type" name="carType" class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select Type</option>
                                        <option value="City Car">CITY CAR</option>
                                        <option value="Mini Bus">MINI BUS</option>
                                        <option value="Shuttle Dieng">SHUTTLE DIENG</option>
                                        <option value="Bus">BUS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="city_district">City / District</label>
                                    <select required id="city_district" name="cityOrDistrict_id" class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select City / District</option>
                                        @foreach($regencies as $regency)
                                        <option value="{{ $regency->id }}">{{ $regency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label" for="status_vehicle">Status</label>
                                    <select required id="status_vehicle" name="statusVehicle" class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select Status</option>
                                        <option value="1">ACTIVE</option>
                                        <option value="0">INACTIVE</option>
                                    </select>
                                </div>
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
                                <label class="form-label" for="car_price">Car Price</label>
                                <input type="text" class="form-control numeral-mask" id="car_price" placeholder="500000" name="carPrice" aria-label="Car Price" required />
                            </div>
                            <!-- Discounted Price -->
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="car_capacity_min">Car Capacity Min</label>
                                    <input type="number" class="form-control" id="car_capacity_min" placeholder="1" name="carCapacity_min" aria-label="Car Capacity Min" required />
                                </div>
                                <div class="col">
                                    <label class="form-label" for="car_capacity_max">Car Capacity Max</label>
                                    <input type="number" class="form-control" id="car_capacity_max" placeholder="4" name="carCapacity_max" aria-label="Car Capacity Min" required />
                                </div>
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
