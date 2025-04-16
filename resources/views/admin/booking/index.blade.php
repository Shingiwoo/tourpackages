@extends('admin.admin_dashboard')
@section('admin')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="mb-6 row g-6">
            <!-- View sales -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-7">
                            <div class="card-body text-nowrap">
                                <h5 class="mb-0 card-title text-capitalize">Best Team! 🎉</h5>
                                <p class="mb-2">Seller for this month</p>
                                <h4 class="mb-1 text-primary">$48.9k</h4>
                                <a href="javascript:;" class="btn btn-primary">View Detail</a>
                            </div>
                        </div>
                        <div class="text-center col-5 text-sm-left">
                            <div class="px-0 pb-0 card-body px-md-4">
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
                        <h5 class="mb-0 card-title">Statistic</h5>
                    </div>
                    <div class="card-body d-flex align-items-end">
                        <div class="w-100">
                            <div class="row gy-3">
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 rounded badge bg-label-danger me-4">
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
                                        <div class="p-2 rounded badge bg-label-info me-4"><i
                                                class="ti ti-brand-booking ti-lg"></i></div>
                                        <div class="card-info">
                                            <h5 class="mb-0">0{{ $bookedStatus }}</h5>
                                            <small>Booked</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 rounded badge bg-label-primary me-4">
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
                                        <div class="p-2 rounded badge bg-label-success me-4">
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
        <div class="mb-4 row g-4">
            <!-- Ajax Sourced Server-side -->
            <div class="card">
                <h5 class="card-header">List Booking</h5>
                <div class="card-datatable table-responsive text-nowrap">
                    <table id="example" class="table datatables-ajax">
                        <thead>
                            <tr>
                                <th class="text-center align-content-center text-primary">#</th>
                                <th class="text-center align-content-center text-primary">Kode Booking</th>
                                <th class="text-center align-content-center text-primary">Client Name</th>
                                <th class="text-center align-content-center text-primary">Type</th>
                                <th class="text-center align-content-center text-primary">Agen</th>
                                <th class="text-center align-content-center text-primary">Start date</th>
                                <th class="text-center align-content-center text-primary">End date</th>
                                <th class="text-center align-content-center text-primary">Status</th>
                                <th class="align-content-center text-primary">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $key => $booking)
                                <tr>
                                    <td class="text-center align-content-center">{{ $key + 1 }}</td>
                                    <td class="align-content-center text-start">{{ $booking->code_booking }}</td>
                                    <td class="align-content-center text-start"><span
                                            class="text-capitalize">{{ $booking->name }}</span></td>
                                    <td class="text-center align-content-center"><span
                                            class="badge bg-{{ $booking->type === 'oneday' ? 'info' : ($booking->type === 'twoday' ? 'primary' : ($booking->type === 'threeday' ? 'success' : ($booking->type === 'fourday' ? 'danger' : 'secondary'))) }} text-uppercase">
                                            {{ $booking->type }}
                                        </span></td>
                                    <td class="align-content-center text-start"><span
                                            class="text-uppercase text-warning">{{ $booking->bookingList->agen->username }}</span>
                                        <br>
                                        <small>{{ $booking->bookingList->agen->company ?? 'Tour Packages' }}</small>
                                    </td>
                                    <td class="text-center align-content-center">
                                        {{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</td>
                                    <td class="text-center align-content-center">
                                        {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</td>
                                    <td class="text-center align-content-center"><span
                                            class="badge bg-{{ $booking->status === 'pending' ? 'danger' : ($booking->status === 'booked' ? 'info' : ($booking->status === 'paid' ? 'primary' : 'success')) }} bg-glow text-uppercase">{{ $booking->status }}</span>
                                    </td>
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
                                                                data-bs-toggle="modal" data-bs-target="#viewBookingData"
                                                                data-id="{{ $booking->id }}"
                                                                data-codeBooking="{{ $booking->code_booking }}"
                                                                data-agenName="{{ $booking->bookingList->agen->username }}"
                                                                data-bookingType="{{ $booking->type }}"
                                                                data-bookingStatus="{{ $booking->status }}"
                                                                data-note="{{ $booking->note }}"
                                                                data-clientName="{{ $booking->name }}"
                                                                data-startDate="{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}"
                                                                data-endDate="{{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}"
                                                                data-startTrip="{{ \Carbon\Carbon::parse($booking->start_trip)->format('H:i') }}"
                                                                data-endTrip="{{ \Carbon\Carbon::parse($booking->end_trip)->format('H:i') }}"
                                                                data-pricePerperson="{{ $booking->price_person }}"
                                                                data-totalUser="{{ $booking->total_user }}"
                                                                @if ($booking->type === 'rent') data-totalUnit="{{ ceil($booking->total_user / 4) }}" @endif
                                                                data-totalCost="{{ $booking->total_price }}"
                                                                data-downPayment="{{ $booking->down_paymet }}"
                                                                data-remainingCost="{{ $booking->remaining_costs }}">
                                                                <span class="ti ti-search ti-md"></span> View
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if (Auth::user()->can('booking.edit'))
                                                        <li>
                                                            <a href="{{ route('edit.booking', $booking->id) }}"
                                                                class="dropdown-item text-warning"> <i
                                                                    class="ti ti-edit"></i> Edit
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if (Auth::user()->can('booking.delete'))
                                                        <li><a href="javascript:void(0)"
                                                                class="dropdown-item text-danger delete-confirm"
                                                                data-id="{{ $booking->id }}"
                                                                data-url="{{ route('delete.booking', $booking->id) }}"> <i
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


    <!-- View Data Modal -->
    <div class="modal fade" id="viewBookingData" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="mb-6 text-center">
                        <h4 class="mb-2">Booking Data</h4>
                    </div>
                    <div id="bookingData">
                        <div class="mb-4 row">
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
                                                <td><span id="booking-type" class="text-uppercase text-primary"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Status :</th>
                                                <td><span id="booking-status" class="text-uppercase text-warning"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Client Name :</th>
                                                <td><span id="client-name" class="text-capitalize"></span></td>
                                            </tr>
                                            <tr>
                                                <th>Start Trip :</th>
                                                <td><span id="start-date" class="text-success"></span> <span id="start-trip" class="text-success"></span></td>
                                            </tr>
                                            <tr>
                                                <th>End Trip :</th>
                                                <td><span id="end-date" class="text-danger"></span> <span id="end-trip" class="text-danger"></span></td>
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
                                            <th class="mx-2 text-center fw-medium" style="width: 40%">Detail</th>
                                            <th class="mx-2 text-center fw-medium">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th><i class="mx-2 ti ti-receipt ti-lg"></i><strong>Biaya Perorang</strong>
                                            </th>
                                            <td class="text-end" id="price-per-person"></td>
                                        </tr>
                                        <tr>
                                            <th><i class="mx-2 ti ti-user ti-lg"></i><strong>Total User</strong></th>
                                            <td class="text-end" id="total-user"></td>
                                        </tr>
                                        <tr>
                                            <th><i class="mx-2 ti ti-car ti-lg"></i><strong>Total Unit</strong></th>
                                            <td class="text-end" id="total-unit"></td>
                                        </tr>

                                        <tr>
                                            <th><i class="mx-2 ti ti-receipt ti-lg"></i><strong>Total Biaya</strong></th>
                                            <td class="text-end" id="total-cost"></td>
                                        </tr>
                                        <tr>
                                            <th><i class="mx-2 ti ti-cash-register ti-lg"></i><strong>Down Payment</strong>
                                            </th>
                                            <td class="text-end" id="down-payment"></td>
                                        </tr>
                                        <tr>
                                            <th><i class="mx-2 ti ti-cash ti-lg"></i><strong>Sisa Biaya</strong></th>
                                            <td class="text-end" id="remaining-cost"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="mt-4 row">
                                    <h6 class="mb-2 text-warning">*Keterangan* : </h6>
                                    <ul>
                                        <li class="mb-2">Note:
                                            <br><span id="note" class="st-italic fs-6 text-info">
                                            </span>
                                        </li>
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
    <!--/ View Data Modal -->
@endsection
