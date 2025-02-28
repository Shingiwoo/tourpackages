@extends('admin.admin_dashboard')
@section('admin')
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Topic and Instructors -->
        <div class="row mb-6 g-6">
            <!-- View sales -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-7">
                            <div class="card-body text-nowrap">
                                <h5 class="card-title mb-0">Congratulations Agen! 🎉</h5>
                                <p class="mb-2">Best seller of the month</p>
                                <h4 class="text-primary mb-1">$48.9k</h4>
                                <a href="javascript:;" class="btn btn-primary">View Agen</a>
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
                        <h5 class="card-title mb-0">Statistics</h5>
                        <small class="text-muted">Updated 1 month ago</small>
                    </div>
                    <div class="card-body d-flex align-items-end">
                        <div class="w-100">
                            <div class="row gy-3">
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-primary me-4 p-2">
                                            <i class="ti ti-packages ti-lg"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">230k</h5>
                                            <small>All Packages</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-info me-4 p-2"><i
                                                class="ti ti-brand-booking ti-lg"></i></div>
                                        <div class="card-info">
                                            <h5 class="mb-0">0{{ $bookedTotal }}</h5>
                                            <small>Booked</small>
                                        </div>
                                    </div>
                                </div>
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

            <!-- Popular Agens -->
            <div class="col-xxl-4 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Best Agen</h5>
                        </div>
                    </div>
                    <div class="px-5 py-4 border border-start-0 border-end-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0 ">Agen</p>
                            <p class="mb-0 ">Booked Tour</p>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach ($agenRanking as $agen)
                            <div class="d-flex justify-content-between align-items-center mb-6">


                                <div class="d-flex align-items-center">
                                    <div>
                                        <div>
                                            <h6 class="mb-0 text-truncate text-capitalize">{{ $agen['agen_name'] }}</h6>
                                            <small class="text-truncate text-body">{{ $agen['agen_company'] }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h6 class="mb-0">0{{ $agen['total_tour'] }} Tour</h6>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!--/ Popular Agens -->

            <!-- Top Tour Packages -->
            <div class="col-xxl-4 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Top Packages</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @forelse ($bestPackages as $package)
                                @if ($package['type'] === 'oneday')
                                    <li class="d-flex mb-6 align-items-center">
                                        <div class="avatar flex-shrink-0 me-4">
                                            <span class="avatar-initial rounded bg-label-primary"><i
                                                    class="ti ti-square-number-1 ti-lg text-primary"></i></span>
                                        </div>
                                        <div class="row w-100 align-items-center">
                                            <div class="col-sm-8 mb-1 mb-sm-0 mb-lg-1 mb-xxl-0">
                                                <a href="{{ route('all.packages') }}">
                                                    <h6 class="mb-0 text-uppercase">{{ $package['name'] }}</h6>
                                                </a>
                                            </div>
                                            <div class="col-sm-4 d-flex justify-content-sm-end">
                                                <div class="badge bg-label-secondary">{{ $package['total_bookings'] }} Book
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                @if ($package['type'] === 'twoday')
                                    <li class="d-flex mb-6 align-items-center">
                                        <div class="avatar flex-shrink-0 me-4">
                                            <span class="avatar-initial rounded bg-label-info"><i
                                                    class="ti ti-square-number-2 ti-lg text-info"></i></span>
                                        </div>
                                        <div class="row w-100 align-items-center">
                                            <div class="col-sm-8 mb-1 mb-sm-0 mb-lg-1 mb-xxl-0">
                                                <a href="{{ route('all.twoday.packages') }}">
                                                    <h6 class="mb-0 text-uppercase">{{ $package['name'] }}</h6>
                                                </a>
                                            </div>
                                            <div class="col-sm-4 d-flex justify-content-sm-end">
                                                <div class="badge bg-label-secondary">{{ $package['total_bookings'] }} Book
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                @if ($package['type'] === 'threeday')
                                    <li class="d-flex mb-6 align-items-center">
                                        <div class="avatar flex-shrink-0 me-4">
                                            <span class="avatar-initial rounded bg-label-success"><i
                                                    class="ti ti-square-number-3 ti-lg text-primary"></i></span>
                                        </div>
                                        <div class="row w-100 align-items-center">
                                            <div class="col-sm-8 mb-1 mb-sm-0 mb-lg-1 mb-xxl-0">
                                                <a href="{{ route('all.threeday.packages') }}">
                                                    <h6 class="mb-0 text-uppercase">{{ $package['name'] }}</h6>
                                                </a>
                                            </div>
                                            <div class="col-sm-4 d-flex justify-content-sm-end">
                                                <div class="badge bg-label-secondary">{{ $package['total_bookings'] }}
                                                    Book</div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                @if ($package['type'] === 'fourday')
                                    <li class="d-flex mb-6 align-items-center">
                                        <div class="avatar flex-shrink-0 me-4">
                                            <span class="avatar-initial rounded bg-label-warning"><i
                                                    class="ti ti-square-number-4 ti-lg text-warning"></i></span>
                                        </div>
                                        <div class="row w-100 align-items-center">
                                            <div class="col-sm-8 mb-1 mb-sm-0 mb-lg-1 mb-xxl-0">
                                                <a href="{{ route('all.fourday.packages') }}">
                                                    <h6 class="mb-0 text-uppercase">{{ $package['name'] }}</h6>
                                                </a>
                                            </div>
                                            <div class="col-sm-4 d-flex justify-content-sm-end">
                                                <div class="badge bg-label-secondary">{{ $package['total_bookings'] }}
                                                    Book</div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                @if ($package['type'] === 'custom')
                                    <li class="d-flex mb-6 align-items-center">
                                        <div class="avatar flex-shrink-0 me-4">
                                            <span class="avatar-initial rounded bg-label-danger"><i
                                                    class="ti ti-table-options ti-lg text-danger"></i></span>
                                        </div>
                                        <div class="row w-100 align-items-center">
                                            <div class="col-sm-8 mb-1 mb-sm-0 mb-lg-1 mb-xxl-0">
                                                <a href="{{ route('all.custom.package') }}">
                                                    <h6 class="mb-0 text-uppercase">{{ $package['name'] }}</h6>
                                                </a>
                                            </div>
                                            <div class="col-sm-4 d-flex justify-content-sm-end">
                                                <div class="badge bg-label-secondary">{{ $package['total_bookings'] }}
                                                    Book</div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                @if ($package['type'] === 'rent')
                                    <li class="d-flex mb-6 align-items-center">
                                        <div class="avatar flex-shrink-0 me-4">
                                            <span class="avatar-initial rounded bg-label-secondary"><i
                                                    class="ti ti-car-suv ti-lg text-info"></i></span>
                                        </div>
                                        <div class="row w-100 align-items-center">
                                            <div class="col-sm-8 mb-1 mb-sm-0 mb-lg-1 mb-xxl-0">
                                                <a href="{{ route('all.rents') }}">
                                                    <h6 class="mb-0 text-uppercase">{{ $package['name'] }}</h6>
                                                </a>
                                            </div>
                                            <div class="col-sm-4 d-flex justify-content-sm-end">
                                                <div class="badge bg-label-secondary">{{ $package['total_bookings'] }}
                                                    Book</div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @empty
                                <li class="d-flex align-items-center">
                                    <div class="avatar flex-shrink-0 me-4">
                                        <span class="avatar-initial rounded bg-label-secondary"><i
                                                class="ti ti-database-off ti-lg text-secondary"></i></span>
                                    </div>
                                    <div class="row w-100 align-items-center">
                                        <div class="col-sm-8 mb-1 mb-sm-0 mb-lg-1 mb-xxl-0">
                                            <h6 class="mb-0 text-uppercase">Tidak ada data Top Packages</h6>
                                        </div>
                                        <div class="col-sm-4 d-flex justify-content-sm-end">
                                            <div class="badge bg-label-secondary">0 Booked</div>
                                        </div>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Top our Packages -->
        </div>
        <!--  Topic and Instructors  End-->

        <div class="card app-calendar-wrapper">
            <div class="row g-0">
                <!-- Calendar Sidebar -->
                <div class="col app-calendar-sidebar border-end" id="app-calendar-sidebar">
                    <div class="px-3 pt-2 mt-2">
                        <!-- inline calendar (flatpicker) -->
                        <div class="inline-calendar"></div>
                    </div>
                    <hr class="mb-6 mx-n4 mt-3" />
                    <div class="px-6 pb-2">
                        <!-- Filter -->
                        <div>
                            <h5>Tour Filters</h5>
                        </div>

                        <div class="form-check form-check-secondary mb-5 ms-2">
                            <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all"
                                checked />
                            <label class="form-check-label" for="selectAll">View All</label>
                        </div>

                        <div class="app-calendar-events-filter text-heading">
                            <div class="form-check form-check-danger mb-5 ms-2">
                                <input class="form-check-input input-filter" type="checkbox" id="select-oneday"
                                    data-value="oneday" checked />
                                <label class="form-check-label" for="select-oneday">Oneday</label>
                            </div>
                            <div class="form-check form-check-primary mb-5 ms-2">
                                <input class="form-check-input input-filter" type="checkbox" id="select-twoday"
                                    data-value="twoday" checked />
                                <label class="form-check-label" for="select-twoday">Twoday</label>
                            </div>
                            <div class="form-check form-check-warning mb-5 ms-2">
                                <input class="form-check-input input-filter" type="checkbox" id="select-threeday"
                                    data-value="threeday" checked />
                                <label class="form-check-label" for="select-threeday">Threeday</label>
                            </div>
                            <div class="form-check form-check-success mb-5 ms-2">
                                <input class="form-check-input input-filter" type="checkbox" id="select-fourday"
                                    data-value="fourday" checked />
                                <label class="form-check-label" for="select-fourday">Fourday</label>
                            </div>
                            <div class="form-check form-check-secondary mb-5 ms-2">
                                <input class="form-check-input input-filter" type="checkbox" id="select-custom"
                                    data-value="custom" checked />
                                <label class="form-check-label" for="select-custom">Custom</label>
                            </div>
                            <div class="form-check form-check-info ms-2">
                                <input class="form-check-input input-filter" type="checkbox" id="select-rent"
                                    data-value="rent" checked />
                                <label class="form-check-label" for="select-rent">Rent</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Calendar Sidebar -->

                <!-- Calendar & Modal -->
                <div class="col app-calendar-content">
                    <div class="card shadow-none border-0">
                        <div class="card-body pb-0">
                            <!-- FullCalendar -->
                            <div id="calendar"></div>
                        </div>
                    </div>
                    <div class="app-overlay"></div>
                    <!-- FullCalendar Offcanvas -->
                    <div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addEventSidebar"
                        aria-labelledby="addEventSidebarLabel">
                        <div class="offcanvas-header border-bottom">
                            <h5 class="offcanvas-title" id="addEventSidebarLabel">Add Event</h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <form class="event-form pt-0" id="eventForm" onsubmit="return false">
                                <div class="mb-5">
                                    <label class="form-label" for="eventStartDate">Start Date</label>
                                    <input type="text" class="form-control" id="eventStartDate" name="eventStartDate"
                                        placeholder="Start Date" />
                                </div>
                                <div class="mb-5">
                                    <label class="form-label" for="eventEndDate">End Date</label>
                                    <input type="text" class="form-control" id="eventEndDate" name="eventEndDate"
                                        placeholder="End Date" />
                                </div>
                                <div class="mb-8">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input allDay-switch"
                                            id="allDaySwitch" />
                                        <label class="form-check-label" for="allDaySwitch">All Day</label>
                                    </div>
                                </div>
                                <hr class="mt-8 mb-8">
                                <div class="mb-8">
                                    <label class="form-label" for="bookingCode">Booking Code</label>
                                    <input type="text" class="form-control" id="bookingCode" name="bookingCode"
                                        placeholder="Booking Code" />
                                </div>
                                <div class="mb-8 ">
                                    <label class="form-label" for="eventDescription">Description</label>
                                    <textarea class="form-control" name="eventDescription" id="eventDescription"></textarea>
                                </div>
                                <div class="d-flex justify-content-sm-between justify-content-start mt-6 gap-2">
                                    <div class="d-flex">
                                        <button type="submit" id="addEventBtn"
                                            class="btn btn-primary btn-add-event me-4">
                                            Add
                                        </button>
                                        <button type="reset" class="btn btn-label-secondary btn-cancel me-sm-0 me-1"
                                            data-bs-dismiss="offcanvas">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /Calendar & Modal -->


                <!-- View Data Modal -->
                <div class="modal fade" id="viewBookingData" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
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
                                                            <td><span id="booking-type"
                                                                    class="text-uppercase text-primary"></span></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Status :</th>
                                                            <td><span id="booking-status"
                                                                    class="text-uppercase text-warning"></span></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Client Name :</th>
                                                            <td><span id="client-name" class="text-capitalize"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Start Trip :</th>
                                                            <td>
                                                                <span id="start-date" class="text-success"></span>
                                                                <span id="start-trip" class="text-success"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>End Trip :</th>
                                                            <td>
                                                                <span id="end-date" class="text-danger"></span>
                                                                <span id="end-trip" class="text-danger"></span>
                                                            </td>
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
                                                        <th class="fw-medium mx-2 text-center" style="width: 40%">Detail
                                                        </th>
                                                        <th class="fw-medium mx-2 text-center">Harga</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th><i class="ti ti-receipt ti-lg mx-2"></i><strong>Biaya
                                                                Perorang</strong></th>
                                                        <td class="text-end" id="price-per-person"></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="ti ti-user ti-lg mx-2"></i><strong>Total
                                                                User</strong></th>
                                                        <td class="text-end" id="total-user"></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="ti ti-receipt ti-lg mx-2"></i><strong>Total
                                                                Biaya</strong></th>
                                                        <td class="text-end" id="total-cost"></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="ti ti-cash-register ti-lg mx-2"></i><strong>Down
                                                                Payment</strong></th>
                                                        <td class="text-end" id="down-payment"></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="ti ti-cash ti-lg mx-2"></i><strong>Sisa
                                                                Biaya</strong></th>
                                                        <td class="text-end" id="remaining-cost"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div>
                                            <a href="{{ route('all.bookings') }}" class="btn btn-info">Detail</a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ View Data Modal -->
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
@endsection
