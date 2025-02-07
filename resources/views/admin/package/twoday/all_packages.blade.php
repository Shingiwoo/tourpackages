@extends('admin.admin_dashboard')
@section('admin')

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Cards with few info -->
    <div class="row g-6 mb-6">
        <div class="col-sm-6 align-self-center text-center">
            <h2 class="text-uppercase text-primary">Two Day Package</h2>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                        <h5 class="mb-1 me-2">{{ $active }}</h5>
                        <p class="mb-0 text-success">Package Active</p>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-success rounded p-2">
                            <i class="ti ti-bulb ti-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                        <h5 class="mb-1 me-2">{{ $inactive }}</h5>
                        <p class="mb-0 text-danger">Package Inactive</p>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-danger rounded p-2">
                            <i class="ti ti-bulb-off ti-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Destinations List-->
    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label text-center">
                        <h5 class="card-title mb-0">Package List</h5>
                    </div>
                    <div class="dt-action-buttons text-end pt-6 pt-md-0">
                        <div class="dt-buttons btn-group flex-wrap">
                            <form id="filterAgenForm" action="" method="GET" style="padding-right: 30px">
                                <select required id="name_agen" name="agen_id" class="select2 form-select"
                                    onchange="updateAgenUrl()">
                                    <option value="">Select Agen</option>
                                    @foreach($agens as $agen)
                                    <option value="{{ $agen->id }}">{{ $agen->username }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <div class="button-group" style="padding-right: 15px">
                                <a id="showAgenPackagesBtn" class="btn btn-warning" href="#">Package By Agen</a>
                            </div>
                            @if (Auth::user()->can('package.add'))
                            <a href="{{ route('generate.twoday.package') }}"
                                class="btn btn-secondary create-new btn-primary waves-effect waves-light">
                                <span><i class="ti ti-plus me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">Add Package</span>
                                </span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-datatable text-nowrap">
                    <table id="example" class="datatables-ajax table" style="width:100%">
                        <thead>
                            <tr>
                                <th class="align-content-center text-center">Sl</th>
                                <th class="align-content-center text-center">Name</th>
                                <th class="align-content-center text-center">Agen</th>
                                <th class="align-content-center text-center">Location</th>
                                <th class="align-content-center text-center">Status</th>
                                @if (Auth::user()->can('package.action'))
                                <th class="align-content-center text-center">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($packages as $key=> $pack )
                            <tr>
                                <td class="align-content-center text-center">{{ $key+1 }}</td>
                                <td class="align-content-center text-center text-uppercase">{{ $pack->name_package }}
                                </td>
                                <td class="align-content-center text-center text-uppercase">{{ $pack->user->username ??
                                    0 }}</td>
                                <td class="align-content-center text-center text-uppercase">{{ $pack->regency->name ?? 0
                                    }}</td>
                                <td class="align-content-center text-center">
                                    @if($pack->status)
                                    <span class="badge bg-label-success rounded p-2"><i class="ti ti-bulb text-success "></i></span>
                                    @else
                                    <span class="badge bg-label-danger rounded p-2"><i class="ti ti-bulb-off text-danger"></i></span>
                                    @endif
                                </td>
                                @if (Auth::user()->can('package.action'))
                                <td class="align-content-center text-center">
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
                                                <li><a class="dropdown-item text-info" href="{{ route('show.twoday.package', $pack->id) }}"><i
                                                            class="ti ti-eye"></i>View</a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->can('package.edit'))
                                                <li><a class="dropdown-item text-warning"
                                                        href="{{ route('edit.twoday.package', $pack->id) }}"><i
                                                            class="ti ti-edit"></i> Edit</a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->can('booking.add'))
                                                <li><a href="javascript:void(0)" class="dropdown-item text-success" data-bs-toggle="modal" data-id="{{ $pack->id }}" data-bs-target="#bookingModal"> <i class="ti ti-shopping-cart-plus"></i>
                                                        Booking
                                                    </a></li>
                                                @endif
                                                @if (Auth::user()->can('package.delete'))
                                                <li><a href="javascript:void(0)"
                                                        class="dropdown-item text-danger delete-confirm"
                                                        data-id="{{ $pack->id }}"
                                                        data-url="{{ route('delete.twoday.package', $pack->id) }}"> <i
                                                            class="ti ti-trash"></i> Delete
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
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="mb-2">Booking Information</h4>
                </div>
                <form id="twodayModalForm" class="row g-6" method="POST" action="{{ route('booking.save') }}">
                    @csrf
                    <div class="row mb-4">
                        <input type="hidden" name="package_id" id="packageId">
                        <div class="col-12 mb-4">
                            <label class="form-label" for="modalClientName">Client Name</label>
                            <input type="text" id="modalClientName" name="modalClientName" class="form-control"
                                placeholder="johndoe007" required />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12 col-md-6 mb-4">
                            <label for="twoday_packageType" class="form-label">Package Type</label>
                            <input type="text" id="twoday_packageType" name="modalPackageType" class="form-control" placeholder="Two Day" value="twoday" readonly/>
                        </div>
                        <div class="col-12 col-md-6 mb-4">
                            <label for="modal_agenName" class="form-label">Agen</label>
                            <select id="modal_agenName" class="select2 form-select" data-allow-clear="true"
                                name="user_id" required>
                                @foreach ( $agens as $agen )
                                <option value="{{ $agen->id }}">{{ $agen->username }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col mb-4" id="total_user_container">
                            <label class="form-label" for="modalTotalUser">Total User</label>
                            <input type="number" id="modalTotalUser" name="modalTotalUser" class="form-control"
                                required />
                        </div>
                        <div class="col mb-4" id="hotel_type_container">
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
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="nomeal" name="mealStatus" value="1" />
                                <label class="form-check-label" for="nomeal">Exclude Meal</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col mb-4">
                            <label for="bs-datepicker-autoclose" class="form-label">Start Date</label>
                            <input type="text" id="bs-datepicker-autoclose" placeholder="MM/DD/YYYY"
                                class="form-control" name="modalStartDate" required />
                        </div>
                        <div class="col mb-4">
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

<script>
    function updateAgenUrl() {
        const agenId = document.getElementById('name_agen').value;
        const showAgenPackagesBtn = document.getElementById('showAgenPackagesBtn');

        if (agenId) {
            showAgenPackagesBtn.href = `{{ url('/packages/twoday/agen') }}/${agenId}`;
            showAgenPackagesBtn.classList.remove('disabled'); // Enable button
        } else {
            showAgenPackagesBtn.href = '#';
            showAgenPackagesBtn.classList.add('disabled'); // Disable button
        }
    }

    // Form Booking twoday Package
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('twodayModalForm');
        const mealStatusCheckbox = document.getElementById('nomeal'); // Ambil elemen checkbox
        const mealStatusInput = document.createElement('input');

        // Buat input hidden untuk mealStatus
        mealStatusInput.type = 'hidden';
        mealStatusInput.name = 'mealStatus';
        form.appendChild(mealStatusInput);

        form.addEventListener('submit', function(e) {
            // Pastikan nilai package type di-set ke 'twoday'
            const packageTypeInput = document.getElementById('twoday_packageType');
            packageTypeInput.value = 'twoday';

            // Update nilai mealStatus sebelum submit
            mealStatusInput.value = mealStatusCheckbox.checked ? 1 : 0;
        });
    });
</script>

@endsection
