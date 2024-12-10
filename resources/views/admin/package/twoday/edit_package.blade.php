@extends('admin.admin_dashboard')
@section('admin')


<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <form id="mydata" action="{{ route('update.twoday.package', $package->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="app-ecommerce">
            <!-- Add Product -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Generate Package Twoday</h4>
                    <p class="mb-0">To Generate a tour package Price</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <a href="{{ route('all.packages') }}">
                        <button type="button" class="btn btn-primary ml-2">Back</button>
                    </a>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </div>

            <div class="row">
                <!-- First column-->
                <div class="col-12 col-lg-6">
                    <!-- Product Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Select Destination</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="name_package">Name Package</label>
                                    <input type="text" class="form-control" id="name_package" placeholder="Name Package"
                                        value="{{$package->name_package}}" name="NamePackage" aria-label="Name Package"
                                        required />
                                </div>
                                <div class="col">
                                    <label class="form-label" for="city_district">City / District</label>
                                    <select required id="city_district" name="cityOrDistrict_id"
                                        class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select City / District</option>
                                        @foreach($regencies as $regency)
                                        <option value="{{ $regency->id }}" {{ $package->regency_id == $regency->id ?
                                            'selected' : '' }}>{{ $regency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="status_package">Status</label>
                                    <select required id="status_package" name="statusPackage"
                                        class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select Status</option>
                                        <option value="1" {{ $package->status == 1 ? 'selected' : '' }}>ACTIVE</option>
                                        <option value="0" {{ $package->status == 0 ? 'selected' : '' }}>INACTIVE
                                        </option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label" for="name_agen">Agen</label>
                                    <select required id="name_agen" name="NameAgen" class="select2 form-select"
                                        data-allow-clear="true">
                                        <option value="">Select Agen</option>
                                        @foreach($agens as $agen)
                                        <option value="{{ $agen->id }}" {{ $package->agen_id == $agen->id ? 'selected' :
                                            '' }}>{{ $agen->username }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Product Information -->
                </div>
                <div class="col-12 col-lg-6">
                    <!-- Destination Card -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Destination & Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-6">
                                <select id="destinations" name="destinations[]" class="select2 form-select" multiple>
                                    @foreach ($destinations as $destination)
                                    <option value="{{ $destination->id }}" {{ in_array($destination->id,
                                        $selectedDestinations) ? 'selected' : '' }}>
                                        {{ $destination->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Description -->
                            <!-- Full Editor -->
                            <div class="col-12">
                                <h5 class="card-title mb-1">Information</h5>
                                <div id="quill-editor" class="mb-3" style="height: 80px;"> </div>
                                <textarea rows="3" class="mb-3 d-none" name="information"
                                    id="quill-editor-area">{{ $package->information }}</textarea>
                            </div>
                            <!-- /Full Editor -->
                        </div>
                    </div>
                    <!-- /Destination Card -->
                </div>
            </div>
        </div>
    </form>
</div>
<!-- / Content -->

@endsection
