@extends('admin.admin_dashboard')
@section('admin')


<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <form id="mydata" action="{{ route('hotel.update', $hotel->id ) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="app-ecommerce">
            <!-- Add Product -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Add a new Hotel</h4>
                    <p class="mb-0">Place name, to create a tour package</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <a href="{{ route('all.hotels') }}">
                        <button type="button" class="btn btn-info ml-2">Back</button>
                    </a>
                    <button type="submit" class="btn btn-primary">Publish</button>
                </div>
            </div>

            <div class="row">
                <!-- First column-->
                <div class="col-12 col-lg-8">
                    <!-- Product Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Hotel Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="name_hotel">Name</label>
                                    <input type="text" class="form-control" id="name_hotel" placeholder="Name Hotel" name="NameHotel" aria-label="Name Hotel" required value="{{ $hotel->name }}"/>
                                </div>
                                <div class="col">
                                    <label class="form-label" for="hotel_type">Hotel Type</label>
                                    <select required id="hotel_type" name="hotelType" class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select Type</option>
                                        <option value="TwoStar" {{ $hotel->type == 'TwoStar' ? 'selected' : '' }}>Bintang 2</option>
                                        <option value="ThreeStar" {{ $hotel->type == 'ThreeStar' ? 'selected' : '' }}>Bintang 3</option>
                                        <option value="FourStar" {{ $hotel->type == 'FourStar' ? 'selected' : '' }}>Bintang 4</option>
                                        <option value="FiveStar" {{ $hotel->type == 'FiveStar' ? 'selected' : '' }}>Bintang 5</option>
                                        <option value="Villa" {{ $hotel->type == 'Villa' ? 'selected' : '' }}>Villa</option>
                                        <option value="Homestay" {{ $hotel->type == 'Homestay' ? 'selected' : '' }}>Homestay</option>
                                        <option value="Cottage" {{ $hotel->type == 'Cottage' ? 'selected' : '' }}>Cottage</option>
                                        <option value="Cabin" {{ $hotel->type == 'Cabin' ? 'selected' : '' }}>Cabin</option>
                                        <option value="Guesthouse" {{ $hotel->type == 'Guesthouse' ? 'selected' : '' }}>Guesthouse</option>
                                        <option value="WithoutAccomodation" {{ $hotel->type == 'WithoutAccomodation' ? 'selected' : '' }}>Without Accomodation</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="city_district">City / District</label>
                                    <select required id="city_district" name="cityOrDistrict_id" class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select City / District</option>
                                        @foreach($regencies as $regency)
                                        <option value="{{ $regency->id }}" {{ $hotel->regency_id == $regency->id ?
                                            'selected' : '' }}>{{ $regency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label" for="status_hotel">Status</label>
                                    <select required id="status_hotel" name="statusHotel" class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select Status</option>
                                        <option value="1" {{ $hotel->status == 1 ? 'selected' : '' }}>ACTIVE</option>
                                        <option value="0" {{ $hotel->status == 0 ? 'selected' : '' }}>INACTIVE
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Product Information -->
                </div>
                <div class="col-12 col-lg-4">
                    <!-- Pricing Card -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Pricing</h5>
                        </div>
                        <div class="card-body">
                            <!-- Base Price -->
                            <div class="mb-6">
                                <label class="form-label" for="hotel_price">Hotel Price</label>
                                <input type="text" class="form-control numeral-mask" id="hotel_price" placeholder="500000" name="hotelPrice" aria-label="Hotel Price" required value="{{ $hotel->price }}"/>
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="extrabed_price">Extrabed Price</label>
                                <input type="text" class="form-control numeral-mask2" id="extrabed_price" placeholder="500000" name="hotelExtrabedPrice" aria-label="Extrabed Price" required value="{{ $hotel->extrabed_price }}"/>
                            </div>
                        </div>
                    </div>
                    <!-- /Pricing Card -->
                </div>
            </div>
        </div>
    </form>
</div>
<!-- / Content -->

@endsection
