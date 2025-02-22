@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-6">
                <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-5">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        <img style="width: 100px; height: 100px"
                            src="{{ !empty($profileData->photo) ? asset('storage/profile/'.$profileData->photo) : asset('assets/img/avatars/no_image.jpg') }}"
                            alt="admin image" class="d-block h-auto ms-0 ms-sm-6 rounded user-profile-img" />
                    </div>
                    <div class="flex-grow-1 mt-3 mt-lg-5">
                        <div
                            class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4 class="mb-2 mt-lg-6">{{ $profileData->name }}</h4>
                                <ul
                                    class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 my-2">
                                    <li class="list-inline-item d-flex gap-2 align-items-center">
                                        <i class="ti ti-user-scan ti-lg"></i><span class="fw-medium text-capitalize">{{ $profileData->username ?? 'Tour package' }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Header -->

    <!-- Navbar pills -->
    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-sm-row mb-6 gap-2 gap-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.profile') }}"><i
                                class="ti-sm ti ti-user-check me-1_5"></i> Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.change.password') }}"><i class="ti-sm ti ti-lock me-1_5"></i>
                            Security</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--/ Navbar pills -->

    <!-- User Profile Content -->
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-5">
            <!-- About User -->
            <div class="card mb-6">
                <div class="card-body">
                    <small class="card-text text-uppercase text-muted small">About</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4">
                            <i class="ti ti-user ti-lg"></i><span class="fw-medium mx-2"><b>Full
                                    Name :</b></span>
                            <span>{{ $profileData->name }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ti ti-activity ti-lg"></i><span class="fw-medium mx-2"><b>Status :</b></span>
                            <span style="text-transform: capitalize">{{ $profileData->status }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ti ti-crown ti-lg"></i><span class="fw-medium mx-2"><b>Role :</b></span>
                            <span style="text-transform: capitalize">{{ $profileData->role }}</span>
                        </li>
                    </ul>
                    <small class="card-text text-uppercase text-muted small">Contacts</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4">
                            <i class="ti ti-phone-call ti-lg"></i><span class="fw-medium mx-2"><b>Contact :</b></span>
                            <span>(+62) {{ $profileData->phone }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ti ti-mail ti-lg"></i><span class="fw-medium mx-2"><b>Email :</b></span>
                            <span style="text-transform:lowercase">{{ $profileData->email}}</span>
                        </li>
                    </ul>
                    <small class="card-text text-uppercase text-muted small">Bank</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4">
                            <i class="ti ti-building-bank ti-lg"></i><span class="fw-medium mx-2"><b>Bank Name
                                    :</b></span>
                            <span style="text-transform:uppercase">{{ $profileData->bank }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ti ti-number ti-lg "></i><span class="fw-medium mx-2"><b>No Rekening :</b></span>
                            <span class="text-start">{{ $profileData->norek }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <!--/ About User -->
            <!-- Profile Overview -->
            <div class="card mb-6">
                <div class="card-body">
                    <small class="card-text text-uppercase text-muted small">Address</small>
                    <ul class="list-unstyled mb-0 mt-3 pt-1">
                        <li class="d-flex align-items-end mb-4">
                            <i class="ti ti-map-pin-check ti-lg"></i> <span>{{ $profileData->address }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <!--/ Profile Overview -->
        </div>
        <div class="col-xl-8 col-lg-7 col-md-7">
            <!-- Change Password -->
            <div class="card mb-6">
                <h5 class="card-header">Change Password</h5>
                <div class="card-body pt-1">
                    <form action="{{ route('admin.password.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-6 col-md-6 form-password-toggle">
                                <label class="form-label" for="old_password">Current Password</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" name="old_password"
                                        id="old_password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-6 col-md-6 form-password-toggle">
                                <label class="form-label" for="new_password">New Password</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control @error('old_password') is-invalid @enderror" type="password" id="new_password" name="new_password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>

                                    @error('old_password')
                                        <span class="text-danger"> {{ $message }} </span>
                                    @enderror

                                </div>
                            </div>

                            <div class="mb-6 col-md-6 form-password-toggle">
                                <label class="form-label" for="new_password_confirmation">Confirm New Password</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control " type="password" name="new_password_confirmation"
                                        id="new_password_confirmation"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-body">Password Requirements:</h6>
                        <ul class="ps-4 mb-0">
                            <li class="mb-4">Minimum 8 characters long - the more, the better</li>
                            <li class="mb-4">At least one lowercase character</li>
                            <li>At least one number, symbol, or whitespace character</li>
                        </ul>
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary me-3">Save changes</button>
                            <button type="reset" class="btn btn-label-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
            <!--/ Change Password -->
        </div>
    </div>
    <!--/ User Profile Content -->
</div>

</div>
@endsection
