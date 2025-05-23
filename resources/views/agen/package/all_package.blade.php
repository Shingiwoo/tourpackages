@extends('agen.agen_dashboard')
@section('agen')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Product List Widget -->
        <div class="card mb-6">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                                <div>
                                    <p class="mb-1"><b>Oneday Package</b></p>
                                    <h4 class="mb-1 text-success">0{{ $countOneday }}</h4>
                                </div>
                                <span class="avatar me-sm-6">
                                    <span class="avatar-initial rounded"><i
                                            class="ti-28px ti ti-pentagon-number-1 text-success"></i></span>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-6" />
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                <div>
                                    <p class="mb-1"><b>Twoday Package</b></p>
                                    <h4 class="mb-1 text-warning">0{{ $countTwoday }}</h4>
                                </div>
                                <span class="avatar p-2 me-lg-6">
                                    <span class="avatar-initial rounded"><i
                                            class="ti-28px ti ti-pentagon-number-2 text-warning"></i></span>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none" />
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                <div>
                                    <p class="mb-1"><b>Threeday Package</b></p>
                                    <h4 class="mb-1 text-info">0{{ $countThreeday }}</h4>
                                </div>
                                <span class="avatar p-2 me-sm-6">
                                    <span class="avatar-initial rounded"><i
                                            class="ti-28px ti ti-pentagon-number-3 text-info"></i></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1"><b>Fourday Package</b></p>
                                    <h4 class="mb-1 text-primary">0{{ $countFourday }}</h4>
                                </div>
                                <span class="avatar p-2">
                                    <span class="avatar-initial rounded"><i
                                            class="ti-28px ti ti-pentagon-number-4 text-primary"></i></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Product List Widget -->

        <!-- Package List-->
        <div class="card">
            <div class="pt-0">
                <div class="dt-bootstrap5 no-footer">
                    <div class="card-header flex-column flex-md-row">
                        <div class="head-label">
                            <h5 class="card-title mb-0">Package List</h5>
                        </div>
                    </div>
                    <div class="card-datatable table-responsive  text-nowrap">
                        <table id="example" class="datatables-ajax table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="align-content-center text-center">Sl</th>
                                    <th class="align-content-center text-center">Name</th>
                                    <th class="align-content-center text-center">City Or Regency</th>
                                    <th class="align-content-center text-center">Duration</th>
                                    <th class="align-content-center text-center">Status</th>
                                    @if (Auth::user()->can('booking.action'))
                                        <th class="align-content-center">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allPackages as $key => $package)
                                    <tr>
                                        <td class="align-content-center text-center">{{ $key + 1 }}</td>
                                        <td class="align-content-center text-center">{{ $package->name_package }}</td>
                                        <td class="align-content-center text-center">
                                            {{ $package->regency->name ?? 'Unknown' }}
                                        </td>
                                        <td class="align-content-center text-center">
                                            <span
                                                class="badge bg-{{ $package->type === 'oneday' ? 'info' : ($package->type === 'twoday' ? 'primary' : ($package->type === 'threeday' ? 'success' : ($package->type === 'fourday' ? 'danger' : 'secondary'))) }} text-uppercase">
                                                {{ $package->type }}
                                            </span>
                                        </td>
                                        <td class="align-content-center text-center">
                                            @if ($package->status)
                                                <i class="ti ti-rosette-discount-check text-success"></i>
                                            @else
                                                <i class="ti ti-playstation-x text-danger"></i>
                                            @endif
                                        </td>
                                        @if (Auth::user()->can('booking.action'))
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
                                                            @if (Auth::user()->can('package.show'))
                                                                <li><a class="dropdown-item text-info"
                                                                        href="{{ route('package.show', ['id' => $package->id, 'type' => $package->type]) }}"><i
                                                                            class="ti ti-device-ipad-horizontal-search"></i>
                                                                        Show</a>
                                                                </li>
                                                            @endif
                                                            @if (Auth::user()->can('booking.add'))
                                                                <li><a href="javascript:void(0)"
                                                                        class="dropdown-item text-success"
                                                                        data-bs-toggle="modal"
                                                                        data-id="{{ $package->id }}"
                                                                        data-name="{{ $package->name_package }}"
                                                                        data-bs-target="#bookingModal"> <i
                                                                            class="ti ti-shopping-cart-plus"></i>
                                                                        Booking
                                                                    </a></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!--/ Icon Dropdown -->
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Destinations List -->

        <!-- Booking Modal -->
        <div class="modal fade" id="bookingModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-6">
                            <h4 class="mb-2">Booking Information</h4>
                        </div>
                        <form id="allpackageModalForm" class="row g-6" method="POST"
                            action="{{ route('booking.store') }}">
                            @csrf
                            <div class="row mb-4">
                                <input type="hidden" name="package_id" id="packageId">
                                <div class="col-12 col-md-6 mb-4">
                                    <label class="form-label" for="modalClientName">Client Name</label>
                                    <input type="text" id="modalClientName" name="modalClientName" class="form-control"
                                        placeholder="johndoe007" required />
                                </div>
                                <div class="col-12 col-md-6 mb-4">
                                    <label class="form-label" for="modalPackageName">Package Name</label>
                                    <input type="text" id="modalPackageName" name="modalPackageName" class="form-control" value="{{ $package->name_package}}" readonly />
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12 col-md-6 mb-4">
                                    <label for="modal_packageType" class="form-label">Package Type</label>
                                    <select id="modal_packageType" class="select2 form-select" data-allow-clear="true"
                                        name="modalPackageType" required>
                                        <option value="">Select Type</option>
                                        <option value="oneday">1 Day</option>
                                        <option value="twoday">2 Day</option>
                                        <option value="threeday">3 Day</option>
                                        <option value="fourday">4 day</option>
                                        <option value="custom">Custom</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-4" id="meal_container">
                                    <div class="form-check mt-8">
                                        <input class="form-check-input" type="checkbox" id="mealStatus" name="mealStatus"
                                            value="1" />
                                        <label class="form-check-label" for="mealStatus">Include Meal</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12 col-md-6 mb-4" id="total_user_container">
                                    <label class="form-label" for="modalTotalUser">Total User</label>
                                    <input type="number" id="modalTotalUser" name="modalTotalUser" class="form-control"
                                        required />
                                </div>
                                <div class="col-12 col-md-6 mb-4" id="hotel_type_container">
                                    <label for="modal_hotelType" class="form-label">Hotel Type</label>
                                    <select id="modal_hotelType" name="modalHotelType" class="select2 form-select"
                                        data-allow-clear="true" required aria-hidden="true">
                                        <option value="">Select Type</option>
                                        <option value="TwoStar">Bintang 2</option>
                                        <option value="ThreeStar">Bintang 3</option>
                                        <option value="FourStar">Bintang 4</option>
                                        <option value="FiveStar">Bintang 5</option>
                                        <option value="Villa">Villa</option>
                                        <option value="Homestay">Homestay</option>
                                        <option value="Cottage">Cottage</option>
                                        <option value="Cabin">Cabin</option>
                                        <option value="Guesthouse">Guesthouse</option>
                                        <option value="WithoutAccomodation">Without Accomodation</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12 col-md-6 mb-4">
                                    <label for="bs-datepicker-autoclose" class="form-label">Start Date</label>
                                    <input type="text" id="bs-datepicker-autoclose" placeholder="MM/DD/YYYY"
                                        class="form-control" name="modalStartDate" required />
                                </div>
                                <div class="col-12 col-md-6 mb-4">
                                    <label for="bs-datepicker-autoclose2" class="form-label">End Date</label>
                                    <input type="text" id="bs-datepicker-autoclose2" placeholder="MM/DD/YYYY"
                                        class="form-control" name="modalEndDate" required />
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary me-3">Submit</button>
                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Booking Modal -->
    </div>

    <script>
        const bookingModal = document.getElementById('bookingModal');
        bookingModal.addEventListener('show.bs.modal', event => {
            // Tombol yang memicu modal
            const button = event.relatedTarget;
            // Ekstrak informasi dari atribut data-*
            const packageName = button.getAttribute('data-name');
            const packageId = button.getAttribute('data-id');
            // Perbarui konten modal
            const modalPackageNameInput = bookingModal.querySelector('#modalPackageName');
            const modalPackageIdInput = bookingModal.querySelector('#packageId');
    
            modalPackageNameInput.value = packageName;
            modalPackageIdInput.value = packageId;
        });

        // Form Booking allpackage
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('allpackageModalForm');
            const mealStatusCheckbox = document.getElementById('nomeal'); // Ambil elemen checkbox
            const mealStatusInput = document.createElement('input');

            // Buat input hidden untuk mealStatus
            mealStatusInput.type = 'hidden';
            mealStatusInput.name = 'mealStatus';
            form.appendChild(mealStatusInput);

            form.addEventListener('submit', function(e) {
                // Update nilai mealStatus sebelum submit
                mealStatusInput.value = mealStatusCheckbox.checked ? 1 : 0;
            });
        });
    </script>
@endsection
