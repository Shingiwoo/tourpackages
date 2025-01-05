@extends('admin.admin_dashboard')
@section('admin')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">All Roles</h4>
                    <p class="mb-0">Set role permissions</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <a href="{{ route('add.roles.permission')}}" type="button" class="btn btn-secondary create-new btn-primary waves-effect waves-light" >
                        <span><i class="ti ti-plus me-sm-1"></i>
                        <span class="d-none d-sm-inline-block">Set Role & Permissions </span></span>
                    </a>
                </div>
            </div>

            <div class="m-2">
                <div class="col-12 mt-4">
                    <h5 class="mb-6">Role & Permissions</h5>
                    <!-- Role & Permissions table -->
                    <div class="card-datatable">
                        <table id="example" class="datatables-ajax table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th class="align-content-center text-center">#</th>
                                    <th class="align-content-center text-center">Role Name</th>
                                    <th class="align-content-center text-center">Permission</th>
                                    <th class="align-content-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as  $key=> $item )
                                <tr>
                                    <td class="align-content-center">{{ $key+1 }}</td>
                                    <td class="align-content-center text-center text-nowrap">
                                        <span class="text-uppercase">{{ $item->name}}</span>
                                    <td class="align-content-center text-center">
                                            @foreach ($item->permissions as $per)
                                            <span class="badge bg-danger m-1">{{ $per->name }}</span>
                                            @endforeach
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
                                                    <li><a class="dropdown-item text-warning" href="{{ route('edit.role.permission', $item->id) }}"><i class="ti ti-edit"></i> Edit</a></li>
                                                    <li><a href="javascript:void(0)" class="dropdown-item text-danger delete-confirm" data-id="{{ $item->id }}" data-url="{{ route('delete.role.permission', $item->id) }}"> <i class="ti ti-trash"></i> Delete
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
                    <!-- Permission table -->
                </div>
            </div>
        </div>
    </div>

    <!-- / Content -->
</div>
<script>
    document.addEventListener('DOMContentLoaded', function (e) {
    (function () {
      // Select All checkbox click
      const selectAll = document.querySelector('#selectAll'),
        checkboxList = document.querySelectorAll('[type="checkbox"]');
      selectAll.addEventListener('change', t => {
        checkboxList.forEach(e => {
          e.checked = t.target.checked;
        });
      });
    })();
  });
</script>
@endsection
