@extends('admin.admin_dashboard')
@section('admin')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-6 g-6">
        <!-- View sales -->
        <div class="col-xl-4">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-7">
                        <div class="card-body text-nowrap">
                            <h5 class="card-title mb-0 text-capitalize">Best Team! 🎉</h5>
                            <p class="mb-2">Seller for this month</p>
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
                                        <h5 class="mb-0">0{{ $pendingStatus }}</h5>
                                        <small>Pending</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-info me-4 p-2"><i
                                            class="ti ti-brand-booking ti-lg"></i></div>
                                    <div class="card-info">
                                        <h5 class="mb-0">0{{ $bookedStatus }}</h5>
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
                                        <h5 class="mb-0">0{{ $paidStatus }}</h5>
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
                                        <h5 class="mb-0">0{{ $finishedStatus }}</h5>
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
                            <th class="align-content-center text-center">Agen</th>
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
                                <td class="align-content-center text-start"><span class="text-uppercase">{{ $booking->bookingList->agen->username}}</span></td>
                                <td class="align-content-center text-center">
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</td>
                                <td class="align-content-center text-center">
                                    {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</td>
                                <td class="align-content-center text-center"><span
                                        class="badge bg-{{ $booking->status === 'pending' ? 'danger' : ($booking->status === 'booked' ? 'info' : ($booking->status === 'paid' ? 'primary' : 'success'))}} bg-glow text-uppercase">{{ $booking->status }}</span></td>
                                <td class="align-content-center">
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="btn-group">
                                            <button type="button"
                                                class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @if (Auth::user()->can('booking.show'))
                                                <li>
                                                    <a type="button" class="dropdown-item text-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewBookingData"
                                                        data-id="{{ $booking->id }}"
                                                        data-codeBooking="{{ $booking->code_booking }}"
                                                        data-agenName="{{ $booking->bookingList->agen->username }}"
                                                        data-bookingType="{{ $booking->type }}"
                                                        data-bookingStatus="{{ $booking->status }}"
                                                        data-clientName="{{ $booking->name }}"
                                                        data-startDate="{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}"
                                                        data-endDate="{{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}"
                                                        data-pricePerperson="{{ $booking->price_person }}"
                                                        data-totalUser="{{ $booking->total_user }}"
                                                        data-totalCost="{{ $booking->total_price }}"
                                                        data-downPayment="{{ $booking->down_paymet }}"
                                                        data-remainingCost="{{ $booking->remaining_costs }}">
                                                        <span class="ti ti-search ti-md"></span> View
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->can('booking.edit'))
                                                <li>
                                                    <a href="javascript:void(0)" class="dropdown-item text-warning"> <i
                                                        class="ti ti-edit"></i> Edit
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->can('booking.delete'))
                                                <li><a href="javascript:void(0)" class="dropdown-item text-danger"> <i
                                                            class="ti ti-trash"></i> Delete
                                                    </a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
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


<!-- Booking Data Modal -->
<div class="modal fade" id="viewBookingData" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="mb-2">Booking Data</h4>
                </div>
                <div id="bookingData">
                    <div class="row mb-4">
                        <div class="col">
                            <div class="card-datatable table-responsive text-nowrap">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Code :</th>
                                            <td id="booking-code"></td>
                                        </tr>
                                        <tr>
                                            <th>Agen :</th>
                                            <td><span id="agen-name" class="text-uppercase text-info"></span></td>
                                        </tr>
                                        <tr>
                                            <th>Type Trip :</th>
                                            <td><span id="booking-type" class="text-uppercase text-primary"></span></td>
                                        </tr>
                                        <tr>
                                            <th>Status :</th>
                                            <td><span id="booking-status" class="text-uppercase text-warning"></span></td>
                                        </tr>
                                        <tr>
                                            <th>Client Name :</th>
                                            <td><span id="client-name" class="text-capitalize"></span></td>
                                        </tr>
                                        <tr>
                                            <th>Start Trip :</th>
                                            <td><span id="start-date" class="text-success"> </span></td>
                                        </tr>
                                        <tr>
                                            <th>End Trip :</th>
                                            <td><span id="end-date" class="text-danger"></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row g-6">
                        <div class="card-datatable table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="fw-medium mx-2 text-center" style="width: 40%">Detail</th>
                                        <th class="fw-medium mx-2 text-center">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th><i class="ti ti-receipt ti-lg mx-2"></i><strong>Biaya Perorang</strong></th>
                                        <td class="text-end" id="price-per-person"></td>
                                    </tr>
                                    <tr>
                                        <th><i class="ti ti-user ti-lg mx-2"></i><strong>Total User</strong></th>
                                        <td class="text-end" id="total-user"></td>
                                    </tr>
                                    <tr>
                                        <th><i class="ti ti-receipt ti-lg mx-2"></i><strong>Total Biaya</strong></th>
                                        <td class="text-end" id="total-cost"></td>
                                    </tr>
                                    <tr>
                                        <th><i class="ti ti-cash-register ti-lg mx-2"></i><strong>Down Payment</strong></th>
                                        <td class="text-end" id="down-payment"></td>
                                    </tr>
                                    <tr>
                                        <th><i class="ti ti-cash ti-lg mx-2"></i><strong>Sisa Biaya</strong></th>
                                        <td class="text-end" id="remaining-cost"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row mt-4">
                                <h6 class="text-warning mb-2">*Keterangan* : </h6>
                                <ul>
                                    <li class="mb-2">Biaya Anak-anak: <strong id="child-cost">Rp 0</strong>
                                        <br><span class="st-italic fs-6 text-info">
                                            Dengan usia 4 - 10 tahun, 11 tahun ke atas biaya penuh
                                        </span>
                                    </li>
                                    <li>Biaya Tambahan WNA: <strong id="additional-cost-wna">Rp 0</strong><br>
                                        <span class="fst-italic fs-6 text-info">
                                            Untuk WNA dikenakan biaya tambahan /orang
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Booking Data Modal -->
@endsection
