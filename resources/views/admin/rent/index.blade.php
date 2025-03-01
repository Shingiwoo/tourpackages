@extends('admin.admin_dashboard')
@section('admin')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Role cards -->
        <div class="row g-6">
            <div class="col-12">
                <h4 class="mt-6 mb-1">All Rent</h4>
                <p class="mb-0">List Rent : Jeep Dieng, Jeep Merapi, Jeep Gumuk, VW Safari</p>
            </div>
            <div class="col-12">
                <!-- Role Table -->
                <div class="card text-nowrap">
                    <div class="card-datatable table-responsive">
                        <table id="example" class="datatables-ajax table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="align-content-center text-center">#</th>
                                    <th class="align-content-center text-center">Name</th>
                                    <th class="align-content-center text-center">Price</th>
                                    <th class="align-content-center text-center">Capacity</th>
                                    @if (Auth::user()->can('agen.action'))
                                    <th class="align-content-center text-center">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allRents as $key=> $item )
                                <tr>
                                    <td class="align-content-center text-center">{{ $key+1 }}</td>
                                    <td class="align-content-center text-center">{{ $item->name }}</td>
                                    <td class="align-content-center text-end">Rp {{ number_format($item->price, 0, ',', '.')  }}</td>
                                    <td class="align-content-center text-center">{{ $item->max_user }}</td>
                                    @if (Auth::user()->can('rent.action'))
                                    <td class="align-content-center text-center">
                                        <!-- Icon Dropdown -->
                                        <div class="col-sm-3 col-sm-6 col-sm-12">
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @if (Auth::user()->can('rent.edit'))
                                                    <li><a class="dropdown-item text-warning"
                                                            href="{{ route('edit.facility', $item->id)}}"><i
                                                                class="ti ti-edit"></i> Edit</a>
                                                    </li>
                                                    @endif
                                                    @if (Auth::user()->can('booking.add'))
                                                    <li><a href="javascript:void(0)" class="dropdown-item text-success" data-bs-toggle="modal" data-id="{{ $item->id }}" data-bs-target="#bookingModal"> <i class="ti ti-shopping-cart-plus"></i>
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
                <!--/ Role Table -->
            </div>
        </div>
        <!--/ Role cards -->
    </div>

    <!-- End Content -->
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
                <form id="rentModalForm" class="row g-6" method="POST" action="{{ route('booking.save') }}">
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
                        <div class="col-12 col-md-4 mb-4">
                            <label for="rent_packageType" class="form-label">Package Type</label>
                            <input type="text" id="rent_packageType" name="modalPackageType" class="form-control" placeholder="Rent" value="rent" readonly/>
                        </div>
                        <div class="col-12 col-md-4 mb-4">
                            <label for="modal_agenName" class="form-label">Agen</label>
                            <select id="modal_agenName" class="select2 form-select" data-allow-clear="true"
                                name="user_id" required>
                                @foreach ( $agens as $agen )
                                <option value="{{ $agen->id }}">{{ $agen->username }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4 mb-4">
                            <label class="form-label" for="modalTotalUser">Total User</label>
                            <input type="number" id="modalTotalUser" name="modalTotalUser" class="form-control"
                                required />
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
                    <div class="row mb-4">
                        <div class="col-12 col-md-6 mb-4">
                            <label for="modalStartTime" class="form-label">Start Time</label>
                            <input type="text" id="modalStartTime" class="form-control" name="modalStartTime" placeholder="HH:MM" required />
                        </div>
                        <div class="col-12 col-md-6 mb-4">
                            <label for="modalEndTime" class="form-label">End Time</label>
                            <input type="text" id="modalEndTime" class="form-control" name="modalEndTime" placeholder="HH:MM" required />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="form-label" for="basic-icon-default-message">Message</label>
                        <div class="input-group input-group-merge">
                          <span id="basic-icon-default-message2" class="input-group-text"><i class="ti ti-message-dots"></i></span>
                          <textarea id="basic-icon-default-message" class="form-control" name="modalNote"  placeholder="Tulis Pesan tambahan jika ada" aria-label="Tulis Pesan tambahan jika ada" aria-describedby="basic-icon-default-message2"></textarea>
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
    // Form Booking rent Package
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('rentModalForm');

        form.addEventListener('submit', function(e) {
            // Pastikan nilai package type di-set ke 'rent'
            const packageTypeInput = document.getElementById('rent_packageType');
            packageTypeInput.value = 'rent';
        });

        flatpickr("#modalStartTime", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });
        flatpickr("#modalEndTime", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });
    });



</script>

@endsection
