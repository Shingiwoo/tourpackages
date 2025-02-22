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
                                           <a href="{{ route('all.packages') }}"><h6 class="mb-0 text-uppercase">{{ $package['name'] }}</h6></a>
                                        </div>
                                        <div class="col-sm-4 d-flex justify-content-sm-end">
                                            <div class="badge bg-label-secondary">{{ $package['total_bookings'] }} Book</div>
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
                                           <a href="{{ route('all.twoday.packages') }}"><h6 class="mb-0 text-uppercase">{{ $package['name'] }}</h6></a>
                                        </div>
                                        <div class="col-sm-4 d-flex justify-content-sm-end">
                                            <div class="badge bg-label-secondary">{{ $package['total_bookings'] }} Book</div>
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
                                           <a href="{{ route('all.threeday.packages') }}"><h6 class="mb-0 text-uppercase">{{ $package['name'] }}</h6></a>
                                        </div>
                                        <div class="col-sm-4 d-flex justify-content-sm-end">
                                            <div class="badge bg-label-secondary">{{ $package['total_bookings'] }} Book</div>
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
                                          <a href="{{ route('all.fourday.packages') }}"><h6 class="mb-0 text-uppercase">{{ $package['name'] }}</h6></a>
                                        </div>
                                        <div class="col-sm-4 d-flex justify-content-sm-end">
                                            <div class="badge bg-label-secondary">{{ $package['total_bookings'] }} Book</div>
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
                                          <a href="{{ route('all.custom.package') }}"><h6 class="mb-0 text-uppercase">{{ $package['name'] }}</h6></a>
                                        </div>
                                        <div class="col-sm-4 d-flex justify-content-sm-end">
                                            <div class="badge bg-label-secondary">{{ $package['total_bookings'] }} Book</div>
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
                                           <a href="{{ route('all.rents') }}"> <h6 class="mb-0 text-uppercase">{{ $package['name'] }}</h6></a>
                                        </div>
                                        <div class="col-sm-4 d-flex justify-content-sm-end">
                                            <div class="badge bg-label-secondary">{{ $package['total_bookings'] }} Book</div>
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
