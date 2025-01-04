@extends('admin.admin_dashboard')
@section('admin')


<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <form id="mydata" action="{{ route('update.permission', $permission->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="app-ecommerce">
            <!-- Add Permission -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Edit Permission</h4>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <a href="{{ route('all.permission') }}">
                        <button type="button" class="btn btn-primary ml-2">Back</button>
                    </a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>

            <div class="row">
                <!-- First column-->
                <div class="col-12">
                    <!-- Permission Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Detail Permission</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="name_permission">Name</label>
                                    <input type="text" class="form-control" id="name_permission" placeholder="Name Permission"
                                        value="{{$permission->name}}" name="permissionName" aria-label="Name Permission"
                                        required />
                                </div>
                                <div class="col">
                                    <label class="form-label" for="permission_group">Group</label>Agen
                                    <select required id="permission_group" name="permissionGroup"
                                        class="select2 form-select" data-allow-clear="true">
                                        <option value="agen" {{ $permission->group_name == 'agen' ? 'selected' : '' }}>Agen</option>
                                        <option value="booking" {{ $permission->group_name == 'booking' ? 'selected' : '' }}>Booking</option>
                                        <option value="package" {{ $permission->group_name == 'package' ? 'selected' : '' }}>Package</option>
                                        <option value="rent" {{ $permission->group_name == 'rent' ? 'selected' : '' }}>Rent</option>
                                        <option value="role" {{ $permission->group_name == 'role' ? 'selected' : '' }}>Role</option>
                                        <option value="permission" {{ $permission->group_name == 'permission' ? 'selected' : '' }}>Permission</option>
                                    </select>
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
