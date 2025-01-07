@extends('admin.admin_dashboard')
@section('admin')

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Cards with few info -->
    <div class="row g-6 mb-6">
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                        <h5 class="mb-1 me-2">{{ $active }}</h5>
                        <p class="mb-0 text-success">Hotel Active</p>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-success rounded p-2">
                            <i class="ti ti-bulb ti-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                        <h5 class="mb-1 me-2">{{ $inactive }}</h5>
                        <p class="mb-0 text-danger">Hotel Inactive</p>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-danger rounded p-2">
                            <i class="ti ti-bulb-off ti-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cards List Widget -->

    <!-- Hotels List-->
    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label text-center">
                        <h5 class="card-title mb-0">Hotels List</h5>
                    </div>
                    @if (Auth::user()->can('hotels.add'))
                    <div class="dt-action-buttons text-end pt-6 pt-md-0">
                        <div class="dt-buttons btn-group flex-wrap">
                            <a href="{{ route('add.hotel') }}"
                                class="btn btn-secondary create-new btn-primary waves-effect waves-light" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button"> <span><i
                                        class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add
                                        Hotel</span></span></a>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-datatable text-nowrap">
                <table id="example" class="datatables-ajax table" style="width:100%">
                    <thead>
                        <tr>
                            <th class="align-content-center text-center">Sl</th>
                            <th class="align-content-center text-center">Name</th>
                            <th class="align-content-center text-center">Price</th>
                            <th class="align-content-center text-center">Type</th>
                            <th class="align-content-center text-center">Status</th>
                            <th class="align-content-center text-center">Location</th>
                            @if (Auth::user()->can('hotels.action'))
                            <th class="align-content-center text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hotels as $key=> $htl )
                        <tr>
                            <td class="align-content-center text-center">{{ $key+1 }}</td>
                            <td class="align-content-center text-center">{{ $htl->name }}</td>
                            <td class="align-content-center text-center">Rp {{ number_format($htl->price, 0, ',', '.') }}</td>
                            <td class="align-content-center text-center">{{ $htl->type }}</td>
                            <td class="align-content-center text-center">
                                @if($htl->status)
                                <span class="badge bg-label-success rounded p-2"><i class="ti ti-bulb text-success "></i></span>
                                @else
                                <span class="badge bg-label-danger rounded p-2"><i class="ti ti-bulb-off text-danger"></i></span>
                                @endif
                            </td>
                            <td class="align-content-center text-center">{{ $htl->regency->name}}</td>
                            @if (Auth::user()->can('hotels.action'))
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
                                            @if (Auth::user()->can('hotels.add'))
                                            <li><a class="dropdown-item text-warning" href="{{ route('edit.hotel', $htl->id) }}"><i class="ti ti-edit"></i> Edit</a></li>
                                            @endif
                                            @if (Auth::user()->can('hotels.delete'))
                                            <li><a href="javascript:void(0)" class="dropdown-item text-danger delete-confirm" data-id="{{ $htl->id }}" data-url="{{ route('delete.hotel', $htl->id) }}"> <i class="ti ti-trash"></i> Delete
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
