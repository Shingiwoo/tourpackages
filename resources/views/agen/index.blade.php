@extends('agen.agen_dashboard')
@section('agen')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Topic and Instructors -->
        <div class="row mb-6 g-6">
            <!-- View sales -->
            <div class="col-xl-4">
                <div class="card">
                  <div class="d-flex align-items-end row">
                    <div class="col-7">
                      <div class="card-body text-nowrap">
                        <h5 class="card-title mb-0 text-capitalize">Welcome {{ $agen->username }}! 🎉</h5>
                        <p class="mb-2">Your seller for this month</p>
                        <h4 class="text-primary mb-1">$48.9k</h4>
                        <a href="javascript:;" class="btn btn-primary">View Detail</a>
                      </div>
                    </div>
                    <div class="col-5 text-center text-sm-left">
                      <div class="card-body pb-0 px-0 px-md-4">
                        <img
                          src="{{ asset('assets/img/illustrations/card-advance-sale.png') }}"
                          height="140"
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
                            <div class="badge rounded bg-label-primary me-4 p-2">
                              <i class="ti ti-packages ti-lg"></i>
                            </div>
                            <div class="card-info">
                              <h5 class="mb-0">{{ $totalPackage }}</h5>
                              <small>Packages</small>
                            </div>
                          </div>
                        </div>
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
                            <div class="badge rounded bg-label-info me-4 p-2"><i class="ti ti-brand-booking ti-lg"></i></div>
                            <div class="card-info">
                              <h5 class="mb-0">{{ $bookedTotal}}</h5>
                              <small>Booked</small>
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
        <!--  Topic and Instructors  End-->


        <!-- Calendar -->
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
        <!--/ Calendar End -->
    </div>
@endsection
