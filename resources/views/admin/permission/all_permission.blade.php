@extends('admin.admin_dashboard')
@section('admin')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Permission Table -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="card-header flex-column flex-md-row">
                        <div class="head-label text-center">
                            <h5 class="card-title mb-0">Permission List</h5>
                        </div>
                        <div class="dt-action-buttons text-end pt-6 pt-md-0">
                            <div class="btn-group">
                            <a href="{{ route('import.permissions') }}"
                                class="btn btn-secondary buttons-collection btn-label-warning me-4 waves-effect waves-light border-none"><span><i class="ti ti-file-import ti-xs me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">Import</span></span></a>
                            </div>
                            <button type="button"
                                class="btn btn-secondary create-new btn-primary waves-effect waves-light"
                                data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                                <span><i class="ti ti-plus me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">Add Permission</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="card-datatable text-nowrap">
                        <table id="example" class="datatables-ajax table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="align-content-center text-center">#</th>
                                    <th class="align-content-center text-center">Permission Name</th>
                                    <th class="align-content-center text-center">Permission Group</th>
                                    <th class="align-content-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $key=> $item )
                                <tr>
                                    <td class="align-content-center text-center">{{ $key+1 }}</td>
                                    <td class="align-content-center text-center">{{ $item->name }}</td>
                                    <td class="align-content-center text-center">
                                        @if ($item->group_name === 'agen')
                                            <span class="badge bg-info text-uppercase"><i class="ti ti-user-hexagon me-2"></i>{{ $item->group_name }}</span>
                                        @elseif ($item->group_name === 'booking')
                                            <span class="badge bg-success text-uppercase"><i class="ti ti-calendar-cog me-2"></i>{{ $item->group_name }}</span>
                                        @elseif ($item->group_name === 'package')
                                            <span class="badge bg-primary text-uppercase"><i class="ti ti-adjustments-search me-2"></i>{{ $item->group_name }}</span>
                                        @elseif ($item->group_name === 'rent')
                                            <span class="badge bg-warning text-uppercase"><i class="ti ti-car-garage me-2"></i>{{ $item->group_name }}</span>
                                        @elseif ($item->group_name === 'role')
                                            <span class="badge bg-danger text-uppercase"><i class="ti ti-lock-access me-2"></i>{{ $item->group_name }}</span>
                                        @elseif ($item->group_name === 'permission')
                                            <span class="badge bg-secondary text-uppercase"><i class="ti ti-user-shield me-2"></i>{{ $item->group_name }}</span>
                                        @elseif ($item->group_name === 'Roles and Permissions')
                                            <span class="badge bg-dark text-uppercase"><i class="ti ti-shield-lock me-2"></i>{{ $item->group_name }}</span>
                                        @endif
                                    </td>
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
                                                    <li><a class="dropdown-item text-warning" href="{{ route('edit.permission', $item->id) }}"><i class="ti ti-edit"></i> Edit</a></li>
                                                    <li><a href="javascript:void(0)" class="dropdown-item text-danger delete-confirm" data-id="{{ $item->id }}" data-url="{{ route('delete.permission', $item->id) }}"> <i class="ti ti-trash"></i> Delete
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
        <!--/ Permission Table -->

        <!-- Modal -->
        <!-- Add Permission Modal -->
        <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-simple">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                        <div class="text-center mb-6">
                            <h4 class="mb-2">Add New Permission</h4>
                            <p>Permissions you may use and assign to your users.</p>
                        </div>
                        <form id="addPermissionForm" method="POST" action="{{ route('permission.store') }}">
                            @csrf
                            <div class="col-12 mb-4">
                                <label class="form-label" for="modalPermissionName">Name</label>
                                <input type="text" id="modalPermissionName" name="permissionName" class="form-control"
                                    placeholder="Permission Name" required />
                            </div>
                            <div class="col-12 mb-4">
                                <label class="form-label" for="modalPermissionGroup">Group Name</label>
                                <input id="modalPermissionGroup" name="permissionGroup" class="form-control"  placeholder="Permission Group" required>
                                </input>
                            </div>
                            <div class="col-12 text-center demo-vertical-spacing">
                                <button type="submit" class="btn btn-primary me-4">Create Permission</button>
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Discard</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--/ Add Permission Modal -->

        <!-- /Modal -->
    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Tangkap tombol yang memicu modal
    const addPermissionButton = document.querySelector('[data-bs-target="#addPermissionModal"]');

    addPermissionButton.addEventListener('click', function () {
        // Reset form modal setiap kali tombol di-klik
        const form = document.getElementById('addPermissionForm');
        form.reset();
    });
});
</script>
@endsection
