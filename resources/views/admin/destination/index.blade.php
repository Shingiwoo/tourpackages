@extends('admin.admin_dashboard')
@section('admin')

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
                                <p class="mb-1"><b>Total Destination</b></p>
                                <h4 class="mb-1">{{ $active }}</h4>
                                <p class="mb-0 text-success">Active</p>
                            </div>
                            <span class="avatar me-sm-6">
                                <span class="avatar-initial rounded"><i
                                        class="ti-28px ti ti-bulb text-success"></i></span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-6" />
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                            <div>
                                <p class="mb-1"><b>Total Destination</b></p>
                                <h4 class="mb-1">{{ $inactive }}</h4>
                                <p class="mb-0 text-warning">Inactive</p>
                            </div>
                            <span class="avatar p-2 me-lg-6">
                                <span class="avatar-initial rounded"><i
                                        class="ti-28px ti ti-bulb-off text-warning"></i></span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none" />
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                            <div>
                                <p class="mb-1"><b>Destination Type</b></p>
                                <h4 class="mb-1">{{ $person }}</h4>
                                <p class="mb-0 text-info">Per Person</p>
                            </div>
                            <span class="avatar p-2 me-sm-6">
                                <span class="avatar-initial rounded"><i
                                        class="ti-28px ti ti-user-dollar text-info"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-1"><b>Destination Type</b></p>
                                <h4 class="mb-1">{{ $flat }}</h4>
                                <p class="mb-0 text-primary">Flat</p>
                            </div>
                            <span class="avatar p-2">
                                <span class="avatar-initial rounded"><i
                                        class="ti-28px ti ti-users-minus text-primary"></i></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Product List Widget -->

    <!-- Destinations List-->
    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label text-center">
                        <h5 class="card-title mb-0">Destinations List</h5>
                    </div>
                    <div class="dt-action-buttons text-end pt-6 pt-md-0">
                        <div class="dt-buttons btn-group flex-wrap">
                            <div class="btn-group">
                                <a href="{{ route('import.destinations') }}"
                                    class="btn btn-secondary buttons-collection btn-label-primary me-4 waves-effect waves-light border-none"><span><i class="ti ti-file-import ti-xs me-sm-1"></i>
                                        <span class="d-none d-sm-inline-block">Import</span></span></a>
                            </div>
                            <a href="{{ route('add.destination') }}"
                                class="btn btn-secondary create-new btn-primary waves-effect waves-light" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button"> <span><i
                                        class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add
                                        Destination</span></span></a>
                        </div>
                    </div>
                </div>
                <div class="card-datatable text-nowrap">
                <table id="example" class="datatables-ajax table" style="width:100%">
                    <thead>
                        <tr>
                            <th class="align-content-center text-center">Sl</th>
                            <th class="align-content-center text-center">Name</th>
                            <th class="align-content-center text-center">Price WNI</th>
                            <th class="align-content-center text-center">Price WNA</th>
                            <th class="align-content-center text-center">Price Type</th>
                            <th class="align-content-center text-center">Max User</th>
                            <th class="align-content-center text-center">Status</th>
                            <th class="align-content-center text-center">Informations</th>
                            <th class="align-content-center text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($destins as $key=> $dts )
                        <tr>
                            <td class="align-content-center text-center">{{ $key+1 }}</td>
                            <td class="align-content-center text-center">{{ $dts->name }}</td>
                            <td class="align-content-center text-center">Rp {{ $dts->price_wni }}</td>
                            <td class="align-content-center text-center">Rp {{ $dts->price_wna }}</td>
                            <td class="align-content-center text-center">{{ $dts->price_type }}</td>
                            <td class="align-content-center text-center">{{ $dts->max_participants }} Person</td>
                            <td class="align-content-center text-center">
                                @if($dts->status)
                                    <i class="ti ti-rosette-discount-check text-success"></i>
                                @else
                                    <i class="ti ti-playstation-x text-danger"></i>
                                @endif
                            </td>
                            <td class="align-content-center text-center">{{ $dts->ket}}</td>
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
                                            <li><a class="dropdown-item text-warning" href="{{ route('edit.destination', $dts->id) }}"><i class="ti ti-edit"></i> Edit</a></li>
                                            <li><a href="javascript:void(0)" class="dropdown-item text-danger delete-confirm" data-id="{{ $dts->id }}" data-url="{{ route('delete.destination', $dts->id) }}"> <i class="ti ti-trash"></i> Delete
                                             </a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!--/ Icon Dropdown -->
                            </td>
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
