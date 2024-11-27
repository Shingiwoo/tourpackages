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
                        <a class="nav-link active" href="{{ route('all.crew') }}">
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
                        <a class="nav-link" href="app-ecommerce-settings-shipping.html">
                            <i class="ti ti-home-infinity ti-sm me-1_5"></i>
                            <span class="align-middle">Facility</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="app-ecommerce-settings-locations.html">
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
                    <!-- Crew Tab -->
                    <form id="mydata" action="{{ route('crew.store') }}" method="POST">
                        @csrf
                        <div class="card mb-6">
                            <div class="card-header">
                                <h5 class="card-title m-0">Data Crew</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6 g-6">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label mb-1" for="min_user">Min User</label>
                                        <input type="number" class="form-control" placeholder="1"
                                            name="minUser" aria-label="Min User" />
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label mb-1" for="max_user">Max User</label>
                                        <input type="number" class="form-control" placeholder="5" name="maxUser" aria-label="Max User" />
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label mb-1" for="total_crew">Total Crew</label>
                                        <input type="number" class="form-control" placeholder="2"
                                            name="totalCrew" aria-label="Total Crew" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-4">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Index Crew Tab -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title m-0">Index Crew</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-6">
                                <div class="col-12 col-md-12">
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="align-content-center text-center">SL</th>
                                                    <th class="align-content-center text-center">Min User</th>
                                                    <th class="align-content-center text-center">Max User</th>
                                                    <th class="align-content-center text-center">Total Crew</th>
                                                    <th class="align-content-center text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                @foreach ($Crew as $key=> $cr )
                                                <tr>
                                                    <td class="align-content-center text-center">#{{ $key+1 }}</td>
                                                    <td class="align-content-center text-center">{{ $cr->min_participants }}
                                                    </td>
                                                    <td class="align-content-center text-center">{{ $cr->max_participants }}
                                                    </td>
                                                    <td class="align-content-center text-center">{{ $cr->num_crew }}</td>
                                                    <td class="align-content-center text-center">
                                                        <div class="dropdown">
                                                            <button type="button"
                                                                class="btn p-0 dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item button" data-bs-toggle="modal"
                                                                    data-bs-target="#enableCrew" data-id="{{ $cr->id }}"
                                                                    data-minUser="{{ $cr->min_participants }}"
                                                                    data-maxUser="{{ $cr->max_participants }}"
                                                                    data-totalCrew="{{ $cr->num_crew }}">
                                                                    <i class="ti ti-pencil me-1"></i> Edit
                                                                </a>
                                                                <a class="dropdown-item button text-danger delete-confirm" data-id="{{ $cr->id }}" data-url="{{ route('delete.crew', $cr->id) }}"><i class="ti ti-trash me-1"></i> Delete</a>
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

<!-- Crew Modal -->
<div class="modal fade" id="enableCrew" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="mb-2">Edit Crew</h4>
                </div>
                <form id="enableCrewForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="row mb-6 g-6">
                        <div class="col-12 col-md-4">
                            <label class="form-label mb-1" for="min_user">Min User</label>
                            <input type="number" id="min_user" class="form-control" placeholder="1"
                                name="minUser" aria-label="Min User" value=""/>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label mb-1" for="max_user">Max User</label>
                            <input type="number" id="max_user" class="form-control" placeholder="6" name="maxUser" aria-label="Max User" value=""/>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label mb-1" for="total_crew">Total Crew</label>
                            <input type="number" id="total_crew" class="form-control" placeholder="5"
                                name="totalCrew" aria-label="Total Crew" value=""/>
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
<!--/ Crew Modal -->

@endsection
