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
                                            <h5 class="mb-0">{{ $pendingStatus }}</h5>
                                            <small>Pending</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-info me-4 p-2"><i
                                                class="ti ti-brand-booking ti-lg"></i></div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ $bookedStatus }}</h5>
                                            <small>Booked</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-primary me-4 p-2">
                                            <i class="ti ti-cash-register ti-lg"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ $paidStatus }}</h5>
                                            <small>Paid</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-success me-4 p-2">
                                            <i class="ti ti-rosette-discount-check ti-lg"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ $finishedStatus }}</h5>
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

        <!-- list Data -->
        <div class="row mb-4 g-4">
            <!-- Ajax Sourced Server-side -->
            <div class="card">
                <h5 class="card-header">List Booking</h5>
                <div class="card-datatable table-responsive text-nowrap">
                    <table id="example" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th class="align-content-center text-center">#</th>
                                <th class="align-content-center text-center">Kode Booking</th>
                                <th class="align-content-center text-center">Client Name</th>
                                <th class="align-content-center text-center">Type</th>
                                <th class="align-content-center text-center">Start date</th>
                                <th class="align-content-center text-center">End date</th>
                                <th class="align-content-center text-center">Status</th>
                                <th class="align-content-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $key => $booking)
                                <tr>
                                    <td class="align-content-center text-center">{{ $key + 1 }}</td>
                                    <td class="align-content-center text-start">{{ $booking->code_booking }}</td>
                                    <td class="align-content-center text-start"><span class="text-capitalize">{{ $booking->name }}</span></td>
                                    <td class="align-content-center text-center"><span
                                        class="badge bg-{{ $booking->type === 'oneday' ? 'info' : ($booking->type === 'twoday' ? 'primary' : ($booking->type === 'threeday' ? 'success' : ($booking->type === 'fourday' ? 'danger' : 'secondary'))) }} text-uppercase">
                                        {{ $booking->type }}
                                    </span></td>
                                    <td class="align-content-center text-center">
                                        {{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</td>
                                    <td class="align-content-center text-center">
                                        {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</td>
                                    <td class="align-content-center text-center"><span
                                            class="badge bg-{{ $booking->status === 'pending' ? 'danger' : ($booking->status === 'booked' ? 'info' : ($booking->status === 'paid' ? 'primary' : 'success'))}} bg-glow text-uppercase">{{ $booking->status }}</span></td>
                                    <td class="align-content-center">
                                        <button type="button" class="btn btn-icon btn-warning waves-effect waves-light"
                                            data-id="{{ $booking->id }}" data-bs-toggle="modal"
                                            data-bs-target="#showBookData">
                                            <span class="ti ti-search ti-md"></span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--/ Ajax Sourced Server-side -->
        </div>
    </div>
    <!-- Show Booking Data Modal -->
    <div class="modal fade" id="showBookData" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2">Detail Booking</h4>
                    </div>
                    <div id="modal-booking-details">
                        <!-- Placeholder for booking details -->
                        <p class="text-center">Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Add New Credit Card Modal -->
@endsection
