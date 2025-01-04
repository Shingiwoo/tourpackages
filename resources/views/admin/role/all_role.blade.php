@extends('admin.admin_dashboard')
@section('admin')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Role Table -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="card-header flex-column flex-md-row">
                        <div class="head-label text-center">
                            <h5 class="card-title mb-0">Roles List</h5>
                        </div>
                        <div class="dt-action-buttons text-end pt-6 pt-md-0">
                            <div class="btn-group">
                            </div>
                            <button type="button"
                                class="btn btn-secondary create-new btn-primary waves-effect waves-light"
                                data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                <span><i class="ti ti-plus me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">Add Roles</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="card-datatable text-nowrap">
                        <table id="example" class="datatables-ajax table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="align-content-center text-center">#</th>
                                    <th class="align-content-center text-center">Role Name</th>
                                    <th class="align-content-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $key=> $item )
                                <tr>
                                    <td class="align-content-center text-center">{{ $key+1 }}</td>
                                    <td class="align-content-center text-center">{{ $item->name }}</td>
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
                                                    <li><a class="dropdown-item text-warning" href="{{ route('edit.role', $item->id) }}"><i class="ti ti-edit"></i> Edit</a></li>
                                                    <li><a href="javascript:void(0)" class="dropdown-item text-danger delete-confirm" data-id="{{ $item->id }}" data-url="{{ route('delete.role', $item->id) }}"> <i class="ti ti-trash"></i> Delete
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
        <!--/ Role Table -->

        <!-- Modal -->
        <!-- Add Role Modal -->
        <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-simple">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                        <div class="text-center mb-6">
                            <h4 class="mb-2">Add New Role</h4>
                            <p>Roles you may use and assign to your users.</p>
                        </div>
                        <form id="addRoleForm" method="POST" action="{{ route('role.store') }}">
                            @csrf
                            <div class="col-12 mb-4">
                                <label class="form-label" for="modalRoleName">Name</label>
                                <input type="text" id="modalRoleName" name="RoleName" class="form-control"
                                    placeholder="Role Name" required />
                            </div>
                            <div class="col-12 text-center demo-vertical-spacing">
                                <button type="submit" class="btn btn-primary me-4">Create Role</button>
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Discard</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--/ Add Role Modal -->

        <!-- /Modal -->
    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Tangkap tombol yang memicu modal
    const addRoleButton = document.querySelector('[data-bs-target="#addRoleModal"]');

    addRoleButton.addEventListener('click', function () {
        // Reset form modal setiap kali tombol di-klik
        const form = document.getElementById('addRoleForm');
        form.reset();
    });
});
</script>
@endsection
