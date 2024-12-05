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
                                <p class="mb-1"><b>Total Packages</b></p>
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
                            class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                            <div>
                                <p class="mb-1"><b>Total Packages</b></p>
                                <h4 class="mb-1">{{ $inactive }}</h4>
                                <p class="mb-0 text-danger">Inactive</p>
                            </div>
                            <span class="avatar me-sm-6">
                                <span class="avatar-initial rounded"><i
                                        class="ti-28px ti ti-bulb-off text-danger"></i></span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-6" />
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
                        <h5 class="card-title mb-0">Package List</h5>
                    </div>
                    <div class="dt-action-buttons text-end pt-6 pt-md-0">
                        <div class="dt-buttons btn-group flex-wrap">
                            <form id="filterAgenForm" action="" method="GET" style="padding-right: 30px">
                                <select required id="name_agen" name="agen_id" class="select2 form-select" onchange="updateAgenUrl()">
                                    <option value="">Select Agen</option>
                                    @foreach($agens as $agen)
                                        <option value="{{ $agen->id }}">{{ $agen->username }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <div class="button-group" style="padding-right: 15px">
                                <a id="showAgenPackagesBtn" class="btn btn-warning" href="#">Package By Agen</a>
                            </div>
                            <a href="{{ route('generate.package') }}" class="btn btn-secondary create-new btn-primary waves-effect waves-light">
                                <span><i class="ti ti-plus me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">Add Package</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-datatable text-nowrap">
                <table id="example" class="datatables-ajax table" style="width:100%">
                    <thead>
                        <tr>
                            <th class="align-content-center text-center">Sl</th>
                            <th class="align-content-center text-center">Name</th>
                            <th class="align-content-center text-center">Agen</th>
                            <th class="align-content-center text-center">Location</th>
                            <th class="align-content-center text-center">Status</th>
                            <th class="align-content-center text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($packages as $key=> $pack )
                        <tr>
                            <td class="align-content-center text-center">{{ $key+1 }}</td>
                            <td class="align-content-center text-center text-uppercase">{{ $pack->name_package }}</td>
                            <td class="align-content-center text-center text-uppercase">{{ $pack->user->username ?? 0 }}</td>
                            <td class="align-content-center text-center text-uppercase">{{ $pack->regency->name ?? 0 }}</td>
                            <td class="align-content-center text-center">
                                @if($pack->status)
                                    <i class="ti ti-rosette-discount-check text-success"></i>
                                @else
                                    <i class="ti ti-playstation-x text-danger"></i>
                                @endif
                            </td>
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
                                            <li><a class="dropdown-item text-info" href=""><i class="ti ti-eye"></i>View</a></li>
                                            <li><a class="dropdown-item text-warning" href="{{ route('edit.package', $pack->id) }}"><i class="ti ti-edit"></i> Edit</a></li>
                                            <li><a href="javascript:void(0)" class="dropdown-item text-danger delete-confirm" data-id="{{ $pack->id }}" data-url="{{ route('delete.package', $pack->id) }}"> <i class="ti ti-trash"></i> Delete
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

<script>
    function updateAgenUrl() {
        const agenId = document.getElementById('name_agen').value;
        const showAgenPackagesBtn = document.getElementById('showAgenPackagesBtn');

        if (agenId) {
            showAgenPackagesBtn.href = `{{ url('/packages/agen') }}/${agenId}`;
            showAgenPackagesBtn.classList.remove('disabled'); // Enable button
        } else {
            showAgenPackagesBtn.href = '#';
            showAgenPackagesBtn.classList.add('disabled'); // Disable button
        }
    }
</script>

@endsection
