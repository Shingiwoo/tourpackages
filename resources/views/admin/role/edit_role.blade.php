@extends('admin.admin_dashboard')
@section('admin')


<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <form id="mydata" action="{{ route('update.role', $role->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="app-ecommerce">
            <!-- Update Role -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Update Role</h4>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                </div>
            </div>

            <div class="row">
                <!-- First column-->
                <div class="col-8">
                    <!-- Permission Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Detail Role</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-6">
                                <div class="col-12 mb-4">
                                    <label class="form-label" for="modalRoleName">Name</label>
                                    <input type="text" id="modalRoleName" name="RoleName" class="form-control"
                                        placeholder="Role Name" value="{{ $role->name }}" required />
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-2 mt-1">Update Role</button>
                                    <a href="{{ route('all.roles') }}">
                                        <button type="button" class="btn btn-info mt-1">Back</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Permission Information -->
                </div>
            </div>
        </div>
    </form>
</div>
<!-- / Content -->

@endsection
