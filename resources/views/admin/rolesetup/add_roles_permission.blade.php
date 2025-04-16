@extends('admin.admin_dashboard')
@section('admin')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <form id="mydata" action="{{ route('role.permission.store') }}" method="POST">
            @csrf
            <div class="app-ecommerce">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                    <div class="d-flex flex-column justify-content-center">
                        <h4 class="mb-1">Add New Roles</h4>
                        <p class="mb-0">Set role permissions</p>
                    </div>
                    <div class="d-flex align-content-center flex-wrap gap-4">
                        <a href="{{ route('all.role.permission') }}">
                            <button type="button" class="btn btn-primary ml-2">Back</button>
                        </a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>

                <div class="m-2">
                    <div class="col-12">
                        <label class="form-label" for="RoleName">Role Name</label>
                        <select required id="RoleName" name="role_id" class="select2 form-select"
                            data-allow-clear="true">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 mt-8">
                        <h5 class="mb-6">Role Permissions</h5>
                        <!-- Permission table -->
                        <div class="table-responsive">
                            <table class="table table-flush-spacing">
                                <tbody>
                                    <tr>
                                        <td class="text-nowrap fw-medium text-heading">
                                            SUPER ADMIN
                                            <i class="ti ti-info-circle" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Allows a full access to the system"></i>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="checkbox" id="selectAll" />
                                                    <label class="form-check-label" for="selectAll"> Select All </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @foreach ($permission_groups as $group )
                                    <tr>
                                        <td class="text-nowrap fw-medium text-heading">
                                            <span class="text-uppercase">{{ $group->group_name}}</span>
                                        <td>
                                            @php
                                                $permissions = App\Models\User::getpermissionByGroupName($group->group_name)
                                            @endphp
                                            <div class="d-flex justify-content-end">
                                                @foreach ($permissions as $permission)
                                                    <div class="form-check mb-0 me-2">
                                                        <input class="form-check-input" type="checkbox" id="dataPermission{{ $permission->id }}" value="{{ $permission->id }}" name="permission[]" />

                                                        <label class="form-check-label" for="dataPermission{{ $permission->id }}"><span class="text-capitalize">{{ $permission->name }}</span> </label>
                                                    </div>
                                                @endforeach
                                            </div>
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
        </form>
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
