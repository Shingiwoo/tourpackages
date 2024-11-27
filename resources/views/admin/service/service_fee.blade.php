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
                        <a class="nav-link active" href="javascript:void(0);">
                            <i class="ti ti-user-screen ti-sm me-1_5"></i>
                            <span class="align-middle">Service Fee</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="app-ecommerce-settings-payments.html">
                            <i class="ti ti-brand-teams ti-sm me-1_5"></i>
                            <span class="align-middle">Crew</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="app-ecommerce-settings-checkout.html">
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

                <div class="tab-pane fade show active" id="store_details" role="tabpanel">
                    <!-- Service Fee Tab -->
                    <form id="mydata" action="{{ route('servicefee.store') }}" method="POST">
                        @csrf
                        <div class="card mb-6">
                            <div class="card-header">
                                <h5 class="card-title m-0">Service Fee</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6 g-6">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label mb-1" for="duration">Duration</label>
                                        <input type="text" class="form-control" placeholder="John Doe"
                                            name="ServiceDuration" aria-label="Duration" />
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label mb-1" for="mark">Mark</label>
                                        <input type="text" class="form-control phone-mask" placeholder="0.12"
                                            name="ServiceMark" aria-label="Mark" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-4">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Index Service Fee Tab -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title m-0">Index Service Fee</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-6">
                                <div class="col-12 col-md-12">
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="align-content-center text-center">SL</th>
                                                    <th class="align-content-center text-center">Duration</th>
                                                    <th class="align-content-center text-center">Mark</th>
                                                    <th class="align-content-center text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                @foreach ($sFee as $key=> $fee )
                                                <tr>
                                                    <td class="align-content-center text-center">#{{ $key+1 }}</td>
                                                    <td class="align-content-center text-center">{{ $fee->duration }}
                                                    </td>
                                                    <td class="align-content-center text-center">{{ $fee->mark }}</td>
                                                    <td class="align-content-center text-center">
                                                        <div class="dropdown">
                                                            <button type="button"
                                                                class="btn p-0 dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item button" data-bs-toggle="modal"
                                                                    data-bs-target="#enableOTP" data-id="{{ $fee->id }}"
                                                                    data-duration="{{ $fee->duration }}"
                                                                    data-mark="{{ $fee->mark }}">
                                                                    <i class="ti ti-pencil me-1"></i> Edit
                                                                </a>
                                                                <a class="dropdown-item button text-danger delete-confirm" data-id="{{ $fee->id }}" data-url="{{ route('delete.service.fee', $fee->id) }}"><i class="ti ti-trash me-1"></i> Delete</a>
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

<!-- Service Fee Modal -->
<div class="modal fade" id="enableOTP" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="mb-2">Edit Service Fee</h4>
                </div>
                <form id="enableOTPForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="row mb-6 g-6">
                        <div class="col-12 col-md-6">
                            <label class="form-label mb-1" for="duration">Duration</label>
                            <input type="text" class="form-control" id="duration" placeholder="Duration"
                                name="ServiceDuration" aria-label="Duration" value="" />
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label mb-1" for="mark">Mark</label>
                            <input type="text" class="form-control" id="mark" placeholder="Mark" name="ServiceMark"
                                aria-label="Mark" value="" />
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
<!--/ Service Fee Modal -->

@endsection
