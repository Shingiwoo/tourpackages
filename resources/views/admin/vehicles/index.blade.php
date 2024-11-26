@extends('admin.admin_dashboard')
@section('admin')

<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-6">
        <!-- Total Vehicles -->
        <div class="col-xl-6 col-md-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Total Vehicle by Status</h5>
                    <small class="text-muted"></small>
                </div>
                <div class="card-body d-flex align-items-end">
                    <div class="w-100">
                        <div class="row gy-3">
                            <div class="col-md-6 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-success me-4 p-2">
                                        <i class="ti ti-rosette-discount-check ti-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $statAct }}</h5>
                                        <small>Active</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-danger me-4 p-2"><i class="ti ti-square-rounded-x ti-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $statInact }}</h5>
                                        <small>Inactive</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Total Vehicles  -->
        <!-- Total Vehicle By Type -->
        <div class="col-xl-6 col-md-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Total Vehicle by Type</h5>
                    <small class="text-muted"></small>
                </div>
                <div class="card-body d-flex align-items-end">
                    <div class="w-100">
                        <div class="row gy-3">
                            <div class="col-md-4 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-primary me-4 p-2">
                                        <i class="ti ti-car ti-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $cityCar }}</h5>
                                        <small>City Car</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-success me-4 p-2"><i class="ti ti-car-suv ti-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $miniBus }}</h5>
                                        <small>Mini Bus</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-info me-4 p-2">
                                        <i class="ti ti-bus ti-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $bus }}</h5>
                                        <small>Bus</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Total Vehicles By Type -->
    </div>
</div>
<!-- / Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Vehicles List-->
    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label text-center">
                        <h5 class="card-title mb-0">Vehicles List</h5>
                    </div>
                    <div class="dt-action-buttons text-end pt-6 pt-md-0">
                        <div class="dt-buttons btn-group flex-wrap">
                            <div class="btn-group">
                                <a href=""
                                    class="btn btn-secondary buttons-collection btn-label-primary me-4 waves-effect waves-light border-none"><span><i
                                            class="ti ti-file-import ti-xs me-sm-1"></i>
                                        <span class="d-none d-sm-inline-block">Import</span></span></a>
                            </div>
                            <a href="" class="btn btn-secondary create-new btn-primary waves-effect waves-light"
                                tabindex="0" aria-controls="DataTables_Table_0" type="button"> <span><i
                                        class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add
                                        Vehicle</span></span></a>
                        </div>
                    </div>
                </div>
                <div class="card-datatable text-nowrap">
                    <table id="example" class="datatables-ajax table" style="width:100%">
                        <thead>
                            <tr>
                                <th class="align-content-center text-center">Sl</th>
                                <th class="align-content-center text-center">Name</th>
                                <th class="align-content-center text-center">Type</th>
                                <th class="align-content-center text-center">Price</th>
                                <th class="align-content-center text-center">Capacity</th>
                                <th class="align-content-center text-center">Location</th>
                                <th class="align-content-center text-center">Status</th>
                                <th class="align-content-center text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ( )
                            <tr>
                                <td class="align-content-center text-center"></td>
                                <td class="align-content-center text-center"></td>
                                <td class="align-content-center text-center">Rp </td>
                                <td class="align-content-center text-center">Rp </td>
                                <td class="align-content-center text-center"></td>
                                <td class="align-content-center text-center"> Person</td>
                                <td class="align-content-center text-center">
                                    @if()
                                    <i class="ti ti-rosette-discount-check text-success"></i>
                                    @else
                                    <i class="ti ti-playstation-x text-danger"></i>
                                    @endif
                                </td>
                                <td class="align-content-center text-center"></td>
                                <td class="align-content-center text-center">
                                    <!-- Icon Dropdown -->
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="btn-group">
                                            <button type="button"
                                                class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item text-warning" href=""><i
                                                            class="ti ti-edit"></i> Edit</a></li>
                                                <li><a href="javascript:void(0)"
                                                        class="dropdown-item text-danger delete-confirm" data-id=""
                                                        data-url=""> <i class="ti ti-trash"></i> Delete
                                                    </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!--/ Icon Dropdown -->
                                </td>
                            </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--/ Destinations List -->
</div>
@endsection
