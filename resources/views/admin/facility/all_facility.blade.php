@extends('admin.admin_dashboard')
@section('admin')


<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-6">
        <!-- Navigation -->
        <div class="col-12 col-lg-3">
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
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="{{ route('all.reservefee') }}">
                            <i class="ti ti-creative-commons-nc ti-sm me-1_5"></i>
                            <span class="align-middle">Reserve Fee</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /Navigation -->

        <!-- Options -->
        <div class="col-12 col-lg-9 pt-6 pt-lg-0">
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
                                    <div class="col">
                                        <label class="form-label mb-1" for="name_facility">Name Facility</label>
                                        <input type="text" id="name_facility" class="form-control" placeholder="Guide"
                                            name="nameFacility" aria-label="Name Facility" />
                                    </div>

                                    <div class="col" id="price_facility_container">
                                        <label class="form-label mb-1" for="price_facility">Price</label>
                                        <input type="text" id="price_facility" class="form-control numeral-mask" placeholder="50000"
                                            name="priceFacility" aria-label="Price" />
                                    </div>
                                    <div class="col" id="maxuser_facility_container">
                                        <label class="form-label mb-1" for="maxuser_facility">Max User</label>
                                        <input type="number" id="maxuser_facility" class="form-control" placeholder="2"
                                            name="maxuserFacility" aria-label="Max User" />
                                    </div>
                                </div>
                                <div class="row mb-6 g-4">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="type_facility">Type</label>
                                        <select required id="type_facility" name="typeFacility"
                                            class="select2 form-select" data-allow-clear="true">
                                            <option value="">Select Type</option>
                                            <option value="flat">Flat</option>
                                            <option value="per_person">Per Person</option>
                                            <option value="per_day">Per Day</option>
                                            <option value="doc">Documentation</option>
                                            <option value="info">Info</option>
                                            <option value="shuttle">Shuttle</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
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
                                                    <th class="align-content-center text-center">Type</th>
                                                    <th class="align-content-center text-center">Capacity</th>
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
                                                    <td class="align-content-center text-center">Rp {{ number_format($fct->price, 0, ',', '.')  }}</td>
                                                    <td class="align-content-center text-center">{{ $fct->type }}
                                                    </td>
                                                    <td class="align-content-center text-center">{{ $fct->max_user }}
                                                    </td>
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
                                                                <a class="dropdown-item button" href="{{ route('edit.facility', $fct->id) }}">
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

@endsection
