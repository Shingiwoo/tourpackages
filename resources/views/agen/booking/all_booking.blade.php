@extends('agen.agen_dashboard')
@section('agen')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-6 g-6">
        <!-- View sales -->
        <div class="col-xl-4">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-7">
                        <div class="card-body text-nowrap">
                            <h5 class="card-title mb-0 text-capitalize">Welcome ! 🎉</h5>
                            <p class="mb-2">Your seller for this month</p>
                            <h4 class="text-primary mb-1">$48.9k</h4>
                            <a href="javascript:;" class="btn btn-primary">View Detail</a>
                        </div>
                    </div>
                    <div class="col-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/card-advance-sale.png') }}" height="140"
                                alt="view agen" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- View sales -->

        <!-- Statistics -->
        <div class="col-xl-8 col-md-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Statistic</h5>
                </div>
                <div class="card-body d-flex align-items-end">
                    <div class="w-100">
                        <div class="row gy-3">
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-danger me-4 p-2">
                                        <i class="ti ti-refresh-alert ti-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">5</h5>
                                        <small>Pending</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-info me-4 p-2"><i
                                            class="ti ti-brand-booking ti-lg"></i></div>
                                    <div class="card-info">
                                        <h5 class="mb-0">10</h5>
                                        <small>Booked</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-primary me-4 p-2">
                                        <i class="ti ti-packages ti-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">20</h5>
                                        <small>On-Trip</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-success me-4 p-2">
                                        <i class="ti ti-rosette-discount-check ti-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">4</h5>
                                        <small>Finished</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Statistics -->
    </div>
    <div class="row mb-4 g-4">
        <!-- Ajax Sourced Server-side -->
        <div class="card">
            <h5 class="card-header">List Booking</h5>
            <div class="card-datatable table-responsive text-nowrap">
                <table id="example" class="datatables-ajax table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Start date</th>
                            <th>End date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!--/ Ajax Sourced Server-side -->
    </div>
</div>

@endsection
