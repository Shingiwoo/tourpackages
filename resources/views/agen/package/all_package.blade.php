@extends('agen.agen_dashboard')
@section('agen')

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Product List Widget -->
    <div class="card mb-6">
        <div class="card-widget-separator-wrapper">
            <div class="card-body card-widget-separator">
                <div class="row gy-4 gy-sm-1">
                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                            <div>
                                <p class="mb-1"><b>Oneday Package</b></p>
                                <h4 class="mb-1 text-success">0{{ $countOneday }}</h4>
                            </div>
                            <span class="avatar me-sm-6">
                                <span class="avatar-initial rounded"><i
                                        class="ti-28px ti ti-pentagon-number-1 text-success"></i></span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-6" />
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                            <div>
                                <p class="mb-1"><b>Twoday Package</b></p>
                                <h4 class="mb-1 text-warning">0{{ $countTwoday }}</h4>
                            </div>
                            <span class="avatar p-2 me-lg-6">
                                <span class="avatar-initial rounded"><i
                                        class="ti-28px ti ti-pentagon-number-2 text-warning"></i></span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none" />
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                            <div>
                                <p class="mb-1"><b>Threeday Package</b></p>
                                <h4 class="mb-1 text-info">0{{ $countThreeday }}</h4>
                            </div>
                            <span class="avatar p-2 me-sm-6">
                                <span class="avatar-initial rounded"><i
                                        class="ti-28px ti ti-pentagon-number-3 text-info"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-1"><b>Fourday Package</b></p>
                                <h4 class="mb-1 text-primary">0{{ $countFourday }}</h4>
                            </div>
                            <span class="avatar p-2">
                                <span class="avatar-initial rounded"><i
                                        class="ti-28px ti ti-pentagon-number-4 text-primary"></i></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Product List Widget -->

    <!-- Package List-->
    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label text-center">
                        <h5 class="card-title mb-0">Package List</h5>
                    </div>
                    <div class="dt-action-buttons text-end pt-6 pt-md-0">
                        <div class="dt-buttons btn-group flex-wrap">
                            <div class="btn-group">
                            @if (Auth::user()->can('booking.add'))
                            <a href=""
                                class="btn btn-secondary create-new btn-primary waves-effect waves-light" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button"> <span><i
                                        class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Booking</span></span></a>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-datatable text-nowrap">
                <table id="example" class="datatables-ajax table" style="width:100%">
                    <thead>
                        <tr>
                            <th class="align-content-center text-center">Sl</th>
                            <th class="align-content-center text-center">Name</th>
                            <th class="align-content-center text-center">Duration</th>
                            <th class="align-content-center text-center">Status</th>
                            @if (Auth::user()->can('booking.action'))
                            <th class="align-content-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paginatedPackages as $key=> $package )
                        <tr>
                            <td class="align-content-center text-center">{{ $key+1 }}</td>
                            <td class="align-content-center text-center">{{ $package->name_package }}</td>
                            <td class="align-content-center text-center">
                                @if($package->type == 'oneday')
                                <span class="badge bg-info text-uppercase">{{ $package->type }}</span>
                                @elseif ($package->type == 'twoday')
                                <span class="badge bg-primary text-uppercase">{{ $package->type }}</span>
                                @elseif ($package->type == 'threeday')
                                <span class="badge bg-success text-uppercase">{{ $package->type }}</span>
                                @else
                                <span class="badge bg-danger text-uppercase">{{ $package->type }}</span>
                                @endif
                            </td>
                            <td class="align-content-center text-center">
                                @if($package->status)
                                    <i class="ti ti-rosette-discount-check text-success"></i>
                                @else
                                    <i class="ti ti-playstation-x text-danger"></i>
                                @endif
                            </td>
                            @if (Auth::user()->can('booking.action'))
                            <td class="align-content-center">
                                <!-- Icon Dropdown -->
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="btn-group">
                                        <button type="button"
                                            class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if (Auth::user()->can('package.show'))
                                            <li><a class="dropdown-item text-info" href=""><i class="ti ti-device-ipad-horizontal-search"></i> Show</a></li>
                                            @endif
                                            @if (Auth::user()->can('booking.add'))
                                            <li><a href="javascript:void(0)" class="dropdown-item text-success" data-id="" data-url=""> <i class="ti ti-shopping-cart-plus"></i> Booking
                                             </a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <!--/ Icon Dropdown -->
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <!--/ Destinations List -->
</div>

@endsection
