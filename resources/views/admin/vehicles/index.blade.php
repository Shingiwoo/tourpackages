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
                                        <div class="badge rounded bg-label-danger me-4 p-2"><i
                                                class="ti ti-square-rounded-x ti-lg"></i>
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
                                        <div class="badge rounded bg-label-success me-4 p-2"><i
                                                class="ti ti-car-suv ti-lg"></i>
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
                                @if (Auth::user()->can('vehicles.add'))
                                <div class="btn-group">
                                    <a href="{{ route('import.vehicles') }}"
                                        class="btn btn-secondary buttons-collection btn-label-warning me-4 waves-effect waves-light border-none"><span><i class="ti ti-file-import ti-xs me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Import</span></span></a>
                                </div>
                                @endif
                                @if (Auth::user()->can('vehicles.add'))
                                <a href="{{ route('add.vehicle') }}"
                                    class="btn btn-secondary create-new btn-primary waves-effect waves-light" tabindex="0"
                                    aria-controls="DataTables_Table_0" type="button"> <span><i
                                            class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add
                                            Vehicle</span></span></a>
                                @endif
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
                                    @if (Auth::user()->can('vehicles.action'))
                                    <th class="align-content-center text-center">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vehicles as $key => $vehicle)
                                    <tr>
                                        <td class="align-content-center text-center">{{ $key + 1 }}</td>
                                        <td class="align-content-center text-center">{{ $vehicle->name }}</td>
                                        <td class="align-content-center text-center">{{ $vehicle->type }}</td>
                                        <td class="align-content-center text-center">Rp
                                            {{ number_format($vehicle->price, 0, ',', '.') }} /day</td>
                                        <td class="align-content-center text-center">
                                            Min : {{ $vehicle->capacity_min }}<br>Max : {{ $vehicle->capacity_max }}
                                        </td>
                                        <td class="align-content-center text-center">{{ $vehicle->regency->name }}</td>
                                        <td class="align-content-center text-center">
                                            @if ($vehicle->status)
                                                <i class="ti ti-rosette-discount-check text-success"></i>
                                            @else
                                                <i class="ti ti-playstation-x text-danger"></i>
                                            @endif
                                        </td>
                                        @if (Auth::user()->can('vehicles.action'))
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
                                                        @if (Auth::user()->can('vehicles.edit'))
                                                        <li><a class="dropdown-item text-warning"
                                                                href="{{ route('edit.vehicle', $vehicle->id) }}"><i
                                                                    class="ti ti-edit"></i> Edit</a></li>
                                                        @endif
                                                        @if (Auth::user()->can('vehicles.delete'))
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-item text-danger delete-confirm"
                                                                data-id="{{ $vehicle->id }}"
                                                                data-url="{{ route('delete.vehicle', $vehicle->id) }}">
                                                                <i class="ti ti-trash"></i> Delete
                                                            </a>
                                                        </li>
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
