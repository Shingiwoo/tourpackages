@extends('agen.agen_dashboard')
@section('agen')
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
                                        <i class="ti ti-user-scan ti-lg"></i><span class="fw-medium">{{
                                            $profileData->username }}</span>
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
                        <a class="nav-link active" href="{{ route('agen.profile') }}"><i
                                class="ti-sm ti ti-user-check me-1_5"></i> Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('agen.change.password') }}"><i class="ti-sm ti ti-lock me-1_5"></i>
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
                            <i class="ti ti-number ti-lg "></i><span class="fw-medium mx-2"><b>Rekening :</b></span>
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
            <form action="{{ route('agen.profile.store') }}" id="formAccountSettings" method="post"
                enctype="multipart/form-data">
                @csrf

                <div class="card mb-6">
                    <!-- Account -->
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-6">
                            <img id="showImage" style="width: 100px; height: 100px"
                                src="{{ asset('assets/img/avatars/no_image.jpg') }}" alt="user-avatar"
                                class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                            <div class="button-wrapper">
                                <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                    <span class="d-none d-sm-block">Upload new photo</span>
                                    <i class="ti ti-upload d-block d-sm-none"></i>
                                    <input type="file" id="upload" name="photo" class="account-file-input" hidden
                                        accept="image/png, image/jpeg, image/gif, image/jpg" />
                                </label>
                                <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row">
                            <div class="mb-4 col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input class="form-control" type="text" id="name" name="name"
                                    value="{{ $profileData->name }}" autofocus />
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input class="form-control" type="text" name="username" id="username"
                                    value="{{ $profileData->username }}" />
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input class="form-control" type="email" id="email" name="email"
                                    placeholder="john.doe@example.com" value="{{ $profileData->email }}" />
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="phone">Phone Number</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">ID (+62)</span>
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        placeholder="851 0000 0000" value="{{ $profileData->phone }}" />
                                </div>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="bank" class="form-label">Bank Name</label>
                                <input type="text" class="form-control" id="bank" name="bank" placeholder="BCA"
                                    value="{{ $profileData->bank }}" />
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="norek" class="form-label">No Rekening</label>
                                <input class="form-control" type="text" id="norek" name="norek" placeholder="1111111111"
                                    value="{{ $profileData->norek }}" />
                            </div>
                            <div class="mb-4 col-md-12">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">Address</span>
                                    <textarea class="form-control" id="address" name="address"
                                        aria-label="With textarea">{{ $profileData->address }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-3">Save changes</button>
                            <button type="reset" class="btn btn-label-secondary">Cancel</button>
                        </div>

                    </div>
                    <!-- /Account -->
                </div>
            </form>
        </div>
    </div>
    <!--/ User Profile Content -->
</div>

</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#upload').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0'])
        });
    });
</script>
@endsection
