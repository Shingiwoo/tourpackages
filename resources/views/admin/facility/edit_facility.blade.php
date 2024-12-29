@extends('admin.admin_dashboard')
@section('admin')


<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <form id="mydata" action="{{ route('facility.update') }}" method="POST">
        @csrf
        <input type="hidden" name="id"  value="{{ $facility->id }}"/>
        <div class="app-ecommerce">
            <!-- Add Product -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Update a old Facility</h4>
                    <p class="mb-0">Place name, to create a tour package</p>
                </div>
                <div class="dt-buttons btn-group">
                    <div class="d-flex align-content-center flex-wrap gap-4">
                        <button type="submit" class="btn btn-primary">Publish</button>
                    </div>
                    <div class="btn-group" style="margin-left: 10px">
                        <a href="{{ route('all.facility') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- First column-->
                <div class="col-12 col-lg-12">
                    <!-- Product Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Facility Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label" for="name_facility">Name</label>
                                    <input type="text" class="form-control" id="name_facility" placeholder="Name Facility" name="nameFacility" aria-label="Name Facility" required value="{{ $facility->name }}"/>
                                </div>

                                <div class="col" id="price_facility_container">
                                    <label class="form-label" for="price_facility">Price</label>
                                    <input type="text" class="form-control numeral-mask" id="price_facility" placeholder="500000" name="priceFacility" aria-label="Price" required value="{{ $facility->price }}"/>
                                </div>
                                <div class="col" id="maxuser_facility_container">
                                    <label class="form-label mb-1" for="maxuser_facility">Max User</label>
                                    <input type="number" id="maxuser_facility" class="form-control" placeholder="2" value="{{ $facility->max_user }}"
                                        name="maxuserFacility" aria-label="Max User" />
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label" for="type_facility">Type</label>
                                    <select required id="type_facility" name="typeFacility" class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select Type</option>
                                        <option value="flat" {{ $facility->type == 'flat' ? 'selected' : '' }}>Flat</option>
                                        <option value="per_person" {{ $facility->type == 'per_person' ? 'selected' : '' }}>Per Person</option>
                                        <option value="per_day" {{ $facility->type == 'per_day' ? 'selected' : '' }}>Per Day</option>
                                        <option value="doc" {{ $facility->type == 'doc' ? 'selected' : '' }}>Documentation</option>
                                        <option value="info" {{ $facility->type == 'info' ? 'selected' : '' }}>Info</option>
                                        <option value="shuttle" {{ $facility->type == 'shuttle' ? 'selected' : '' }}>Shuttle</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label" for="city_district">City / District</label>
                                    <select required id="city_district" name="cityOrDistrict_id" class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select City / District</option>
                                        @foreach($regencies as $regency)
                                            <option value="{{ $regency->id }}" {{ $facility->regency_id == $regency->id ? 'selected' : '' }}>
                                                {{ $regency->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Product Information -->
                </div>
            </div>
        </div>
    </form>
</div>
<!-- / Content -->

@endsection
