@extends('admin.admin_dashboard')
@section('admin')


<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-6">
        <!-- Navigation -->
        <div class="col-12 col-lg-4">
            <div class="d-flex justify-content-between flex-column mb-4 mb-md-0">
                <h5 class="mb-4">Other Service</h5>
                <ul class="nav nav-align-left nav-pills flex-column">
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="{{ route('all.service') }}">
                            <i class="ti ti-user-screen ti-sm me-1_5"></i>
                            <span class="align-middle">Service Fee</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="{{ route('all.crew') }}">
                            <i class="ti ti-brand-teams ti-sm me-1_5"></i>
                            <span class="align-middle">Crew</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="{{ route('all.agen.fee') }}">
                            <i class="ti ti-user-pentagon ti-sm me-1_5"></i>
                            <span class="align-middle">Agen Fee</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link active" href="{{ route('all.facility') }}">
                            <i class="ti ti-home-infinity ti-sm me-1_5"></i>
                            <span class="align-middle">Facility</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="{{ route('all.meals') }}">
                            <i class="ti ti-soup ti-sm me-1_5"></i>
                            <span class="align-middle">Meal</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /Navigation -->

        <!-- Options -->
        <div class="col-12 col-lg-8 pt-6 pt-lg-0">
            <div class="tab-content p-0">

                <div class="tab-pane fade show active" role="tabpanel">
                    <!-- Facility Tab -->
                    <form id="mydata" action="{{ route('facility.store') }}" method="POST">
                        @csrf
                        <div class="card mb-6">
                            <div class="card-header">
                                <h5 class="card-title m-0">Facility</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6 g-4">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label mb-1" for="name_facility">Name Facility</label>
                                        <input type="text" id="name_facility" class="form-control" placeholder="Guide"
                                            name="nameFacility" aria-label="Name Facility" />
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label mb-1" for="price_facility">Price</label>
                                        <input type="text" id="price_facility" class="form-control numeral-mask" placeholder="50000"
                                            name="priceFacility" aria-label="Price" />
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="city_district">City / District</label>
                                        <select required id="city_district" name="cityOrDistrict_id"
                                            class="select2 form-select" data-allow-clear="true">
                                            <option value="">Select City / District</option>
                                            @foreach($cities as $regency)
                                            <option value="{{ $regency->id }}">{{ $regency->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-4">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Index Facility Tab -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title m-0">Index Facility</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-6">
                                <div class="col-12 col-md-12">
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="align-content-center text-center">SL</th>
                                                    <th class="align-content-center text-center">Name</th>
                                                    <th class="align-content-center text-center">Price</th>
                                                    <th class="align-content-center text-center">City / District</th>
                                                    <th class="align-content-center text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                @foreach ($facilities as $key=> $fct )
                                                <tr>
                                                    <td class="align-content-center text-center">#{{ $key+1 }}</td>
                                                    <td class="align-content-center text-center">{{ $fct->name }}
                                                    </td>
                                                    <td class="align-content-center text-center">{{ $fct->price }}</td>
                                                    <td class="align-content-center text-center">{{ $fct->regency->name }}
                                                    </td>
                                                    <td class="align-content-center text-center">
                                                        <div class="dropdown">
                                                            <button type="button"
                                                                class="btn p-0 dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item button" data-bs-toggle="modal"
                                                                    data-bs-target="#enableFacility" data-id="{{ $fct->id }}"
                                                                    data-nameFacility="{{ $fct->name }}"
                                                                    data-priceFacility="{{ $fct->price }}"
                                                                    data-city="{{ $fct->regency_id }}">
                                                                    <i class="ti ti-pencil me-1"></i> Edit
                                                                </a>
                                                                <a class="dropdown-item button text-danger delete-confirm" data-id="{{ $fct->id }}" data-url="{{ route('delete.facility', $fct->id) }}"><i class="ti ti-trash me-1"></i> Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
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
        <!-- /Options-->
    </div>
</div>
<!-- / Content -->

<!-- Facility Modal -->
<div class="modal fade" id="enableFacility" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="mb-2">Edit Facility</h4>
                </div>
                <form id="enableFacilityForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="row mb-6 g-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label mb-1" for="facilityName">Name Facility</label>
                            <input type="text" id="facilityName" class="form-control" placeholder="Guide"
                                name="nameFacility" aria-label="Name Facility" value=""/>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label mb-1" for="facilityPrice">Price</label>
                            <input type="text" id="facilityPrice" class="form-control numeral-mask" placeholder="600000"
                                name="priceFacility" aria-label="Price" value=""/>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="districtCity">City / District</label>
                            <select required id="districtCity" name="cityOrDistrict_id"
                                class="select2 form-select" data-allow-clear="true">
                                <option value="">Select City / District</option>
                                @foreach($cities as $regency)
                                    <option value="{{ $regency->id }}"
                                        {{ isset($facilities->cityOrDistrict_id) && $facilities->cityOrDistrict_id == $regency->id ? 'selected' : '' }}>
                                        {{ $regency->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-4">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!--/ Facility Modal -->

@endsection
