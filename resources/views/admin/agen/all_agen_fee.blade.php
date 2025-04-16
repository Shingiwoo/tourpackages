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
                        <a class="nav-link active" href="{{ route('all.agen.fee') }}">
                            <i class="ti ti-user-pentagon ti-sm me-1_5"></i>
                            <span class="align-middle">Agen Fee</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="{{ route('all.facility') }}">
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
        <div class="col-12 col-lg-8 pt-6 pt-lg-0">
            <div class="tab-content p-0">

                <div class="tab-pane fade show active" role="tabpanel">
                    <!-- Crew Tab -->
                    <form id="mydata" action="{{ route('update.agen.fee') }}" method="POST">
                        @csrf

                        <input type="hidden" name="id" value="{{ $agenFee->id }}"/>
                        <div class="card mb-6">
                            <div class="card-header">
                                <h5 class="card-title m-0">Data Agen Fee</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6 g-6">
                                    <div class="col-12 col-md-12">
                                        <label class="form-label mb-1" for="agen_fee">Agen Fee Perday</label>
                                        <input type="text" id="agen_fee" class="form-control numeral-mask" placeholder="25000" name="agenFee" aria-label="Agen Fee Perday" value="{{ $agenFee->price }}"/>
                                    </div>
                                </div>
                                @if (Auth::user()->can('service.add'))
                                <div class="d-flex justify-content-end gap-4">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Options-->
    </div>
</div>
<!-- / Content -->

@endsection
