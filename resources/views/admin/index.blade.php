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
                            <div class="badge rounded bg-label-info me-4 p-2"><i class="ti ti-brand-booking ti-lg"></i></div>
                            <div class="card-info">
                              <h5 class="mb-0">8.549k</h5>
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
                              <h5 class="mb-0">1.423k</h5>
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
                              <h5 class="mb-0">$9745</h5>
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
                        <div class="d-flex justify-content-between align-items-center mb-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar me-4">
                                    <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Avatar"
                                        class="rounded-circle" />
                                </div>
                                <div>
                                    <div>
                                        <h6 class="mb-0 text-truncate">Maven Analytics</h6>
                                        <small class="text-truncate text-body">Business Intelligence</small>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <h6 class="mb-0">33</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Popular Agens -->

            <!-- Top Tour Packages -->
            <div class="col-xxl-4 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Top Tour Packages</h5>
                        <div class="dropdown">
                            <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1"
                                type="button" id="topCourses" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="ti ti-dots-vertical ti-md text-muted"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="topCourses">
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                <a class="dropdown-item" href="javascript:void(0);">Download</a>
                                <a class="dropdown-item" href="javascript:void(0);">View All</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex mb-6 align-items-center">
                                <div class="avatar flex-shrink-0 me-4">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class="ti ti-video ti-lg"></i></span>
                                </div>
                                <div class="row w-100 align-items-center">
                                    <div class="col-sm-8 mb-1 mb-sm-0 mb-lg-1 mb-xxl-0">
                                        <h6 class="mb-0">Videography Basic Design Course</h6>
                                    </div>
                                    <div class="col-sm-4 d-flex justify-content-sm-end">
                                        <div class="badge bg-label-secondary">1.2k Views</div>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-6 align-items-center">
                                <div class="avatar flex-shrink-0 me-4">
                                    <span class="avatar-initial rounded bg-label-info"><i
                                            class="ti ti-code ti-lg"></i></span>
                                </div>
                                <div class="row w-100 align-items-center">
                                    <div class="col-sm-8 mb-1 mb-sm-0 mb-lg-1 mb-xxl-0">
                                        <h6 class="mb-0">Basic Front-end Development Course</h6>
                                    </div>
                                    <div class="col-sm-4 d-flex justify-content-sm-end">
                                        <div class="badge bg-label-secondary">834 Views</div>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-6 align-items-center">
                                <div class="avatar flex-shrink-0 me-4">
                                    <span class="avatar-initial rounded bg-label-success"><i
                                            class="ti ti-camera ti-lg"></i></span>
                                </div>
                                <div class="row w-100 align-items-center">
                                    <div class="col-sm-8 mb-1 mb-sm-0 mb-lg-1 mb-xxl-0">
                                        <h6 class="mb-0">Basic Fundamentals of Photography</h6>
                                    </div>
                                    <div class="col-sm-4 d-flex justify-content-sm-end">
                                        <div class="badge bg-label-secondary">3.7k Views</div>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-6 align-items-center">
                                <div class="avatar flex-shrink-0 me-4">
                                    <span class="avatar-initial rounded bg-label-warning"><i
                                            class="ti ti-brand-dribbble ti-lg"></i></span>
                                </div>
                                <div class="row w-100 align-items-center">
                                    <div class="col-sm-8 mb-1 mb-sm-0 mb-lg-1 mb-xxl-0">
                                        <h6 class="mb-0">Advance Dribble Base Visual Design</h6>
                                    </div>
                                    <div class="col-sm-4 d-flex justify-content-sm-end">
                                        <div class="badge bg-label-secondary">2.5k Views</div>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="avatar flex-shrink-0 me-4">
                                    <span class="avatar-initial rounded bg-label-danger"><i
                                            class="ti ti-microphone-2 ti-lg"></i></span>
                                </div>
                                <div class="row w-100 align-items-center">
                                    <div class="col-sm-8 mb-1 mb-sm-0 mb-lg-1 mb-xxl-0">
                                        <h6 class="mb-0">Your First Singing Lesson</h6>
                                    </div>
                                    <div class="col-sm-4 d-flex justify-content-sm-end">
                                        <div class="badge bg-label-secondary">948 Views</div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Top our Packages -->
        </div>
        <!--  Topic and Instructors  End-->

        <!-- Course datatable -->
        <div class="card">
            <div class="table-responsive mb-4">
                <table class="table table-sm datatables-academy-course">
                    <thead class="border-top">
                        <tr>
                            <th></th>
                            <th></th>
                            <th>Course Name</th>
                            <th>Time</th>
                            <th class="w-25">Progress</th>
                            <th class="w-25">Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- Course datatable End -->
    </div>
@endsection
