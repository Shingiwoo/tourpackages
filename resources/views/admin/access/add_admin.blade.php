@extends('admin.admin_dashboard')
@section('admin')
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="col-lg-9 mb-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add User Admin</h5>
                    <small class="text-muted float-end">Set User With Role</small>
                </div>
                <div class="card-body">
                    <form class="border rounded p-3 p-md-5" action="{{ route('store.admin')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col mb-6">
                                <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                            class="ti ti-user"></i></span>
                                    <input type="text" class="form-control" id="basic-icon-default-fullname"
                                        placeholder="John Doe" aria-label="John Doe" name="fullname"
                                        aria-describedby="basic-icon-default-fullname2">
                                </div>
                            </div>
                            <div class="col mb-6">
                                <label class="form-label" for="basic-icon-default-fullname">Username</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                            class="ti ti-user-square"></i></span>
                                    <input type="text" class="form-control" id="basic-icon-default-fullname"
                                        placeholder="John Doe" aria-label="John Doe" name="alias"
                                        aria-describedby="basic-icon-default-fullname2">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-6">
                                <label class="form-label" for="basic-icon-default-email">Email</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                    <input type="text" id="basic-icon-default-email" class="form-control"
                                        placeholder="john.doe" aria-label="john.doe" name="youremail"
                                        aria-describedby="basic-icon-default-email2">
                                </div>
                                <div class="form-text">You can use letters, numbers &amp; periods</div>
                            </div>
                            <div class="col mb-6">
                                <label class="form-label" for="basic-icon-default-phone">Phone No</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-phone2" class="input-group-text"><i
                                            class="ti ti-phone"></i></span>
                                    <input type="text" id="basic-icon-default-phone" class="form-control phone-mask"
                                        name="yourphone" placeholder="658 799 8941" aria-label="658 799 8941"
                                        aria-describedby="basic-icon-default-phone2">
                                </div>
                            </div>
                            <div class="col mb-6">
                                <label class="form-label" for="access">Access</label>
                                <select required id="access" name="roleID" class="select2 form-select"
                                    data-allow-clear="true">
                                    <option value="">Select Access</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-6">
                                <div class="form-password-toggle">
                                    <label class="form-label" for="multicol-password">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="multicol-password" class="form-control"
                                            name="password" placeholder="············"
                                            aria-describedby="multicol-password2">
                                        <span class="input-group-text cursor-pointer" id="multicol-password2"><i
                                                class="ti ti-eye-off"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-6">
                                <div class="form-password-toggle">
                                    <label class="form-label" for="multicol-confirm-password">Confirm Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="multicol-confirm-password" class="form-control"
                                            name="password_confirmation" placeholder="············"
                                            aria-describedby="multicol-confirm-password2">
                                        <span class="input-group-text cursor-pointer" id="multicol-confirm-password2"><i
                                                class="ti ti-eye-off"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="form-label" for="basic-icon-default-message">Address</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-message2" class="input-group-text"><i
                                        class="ti ti-map-pin"></i></span>
                                <textarea id="basic-icon-default-message" class="form-control" name="fulladdress"
                                    placeholder="Nr. Wall Street" aria-label="Nr. Wall Street"
                                    aria-describedby="basic-icon-default-message2"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
