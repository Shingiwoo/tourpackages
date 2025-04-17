@extends('admin.admin_dashboard')
@section('admin')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">List Custom Package</h4>
                    <p class="mb-0">Special saved packages can be ordered</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <a href="{{ route('calculate.custom.package') }}" class="btn btn-primary">Create</a>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-datatable table-responsive pt-0">
                    <table id="example" class="datatables-ajax table" style="width:100%">
                        <thead>
                            <tr>
                                <th class="align-content-center text-center">#</th>
                                <th class="align-content-center text-center">Name</th>
                                <th class="align-content-center text-center">Agen</th>
                                <th class="align-content-center text-center">Duration</th>
                                <th class="align-content-center text-center">Total User</th>
                                <th class="align-content-center text-center">Price Perperson</th>
                                <th class="align-content-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customData as $key => $data)
                                <tr>
                                    <td class="align-content-center text-center">{{ $key + 1 }}</td>
                                    <td class="align-content-center text-center text-uppercase">{{ $data['package_name'] }}</td>
                                    <td class="align-content-center text-center text-uppercase">{{ $data['agen_name'] }}</td>
                                    <td class="align-content-center text-center">{{ $data['DurationPackage'] }} Days</td>
                                    <td class="align-content-center text-center">{{ $data['participants'] }}</td>
                                    <td class="align-content-center text-center">Rp
                                        {{ number_format($data['costPerPerson'], 0, ',', '.') }}
                                    </td>
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
                                                        <li><a type="button" class="dropdown-item text-info"
                                                                data-id="{{ $data['id'] }}" data-bs-toggle="modal"
                                                                data-bs-target="#showData">
                                                                <i class="ti ti-eye"></i> Show
                                                            </a></li>
                                                    @endif
                                                    @if (Auth::user()->can('booking.add'))
                                                    <li><a href="javascript:void(0)" class="dropdown-item text-success" data-bs-toggle="modal" data-id="{{ $data['id'] }}" data-name="{{ $data['package_name'] }}" data-bs-target="#bookingModal"> <i class="ti ti-shopping-cart-plus"></i>
                                                            Booking
                                                        </a></li>
                                                    @endif
                                                    @if (Auth::user()->can('package.delete'))
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-item text-danger delete-confirm"
                                                                data-id="{{ $data['id'] }}"
                                                                data-url="{{ route('delete.custom.package', $data['id']) }}">
                                                                <i class="ti ti-trash"></i> Delete
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        <!--/ Icon Dropdown -->
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No Custom Packages Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- End Content -->
    </div>

    <!-- Custom Save Modal -->
    <div class="modal fade" id="showData" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="address-title mb-2">Custom Package : <br><span class="package-name"></span></h4>
                        <p class="address-subtitle">Trip Detail for <span class="duration"></span> day(s) <span
                                class="night"></span> night(s)</p>
                    </div>
                    <div class="row mb-4">
                        <h5 class="text-warning mb-2">*Destinasi* :</h5>
                        <div class="col">
                            <div class="demo-inline-spacing mt-4">
                                <ol class="list-group destinations">
                                    <!-- Destinations will be dynamically populated -->
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <h5 class="text-warning mb-2">*Fasilitas* :</h5>
                        <div class="col">
                            <div class="demo-inline-spacing mt-4">
                                <ol class="list-group facilities">
                                    <!-- Facilities will be dynamically populated -->
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="card-datatable table-responsive text-nowrap">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="fw-medium mx-2 text-center" style="width: 40%">Detail</th>
                                    <th class="fw-medium mx-2 text-center">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th><i class="ti ti-receipt ti-lg mx-2"></i><strong>Biaya Perorang</strong></th>
                                    <td class="text-end perperso-cost">Rp -</td>
                                </tr>
                                <tr>
                                    <th><i class="ti ti-user ti-lg mx-2"></i><strong>Total User</strong></th>
                                    <td class="text-end total-user">0 orang</td>
                                </tr>
                                <tr>
                                    <th><i class="ti ti-receipt ti-lg mx-2"></i><strong>Total Biaya</strong></th>
                                    <td class="text-end total-cost">Rp -</td>
                                </tr>
                                <tr>
                                    <th><i class="ti ti-cash-register ti-lg mx-2"></i><strong>Down Payment</strong></th>
                                    <td class="text-end down-payment">Rp -</td>
                                </tr>
                                <tr>
                                    <th><i class="ti ti-cash ti-lg mx-2"></i><strong>Sisa Biaya</strong></th>
                                    <td class="text-end remaining-costs">Rp -</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row mt-4">
                            <h6 class="text-warning mb-2">*Keterangan* : </h6>
                            <ul>
                                <li class="mb-2">Biaya Anak-anak: <strong class="child-cost">Rp -</strong> <br><span
                                        class="st-italic fs-6 text-info">Dengan usia 4 - 10 tahun, 11 tahun keatas biaya
                                        penuh</span></li>
                                <li>Biaya Tambahan WNA: <strong class="additional-cost-wna">Rp -</strong><br><span
                                        class="fst-italic fs-6 text-info">Untuk WNA dikenakan biaya tambahan /orang</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Custom Save Modal -->

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2">Booking Information</h4>
                    </div>
                    <form id="customModalForm" class="row g-6" method="POST" action="{{ route('booking.save') }}">
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
                                <input type="text" id="modalPackageName" name="modalPackageName" class="form-control"
                                value="{{ $data['package_name'] }}" readonly />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12 col-md-6 mb-4">
                                <label for="custom_packageType" class="form-label">Package Type</label>
                                <input type="text" id="custom_packageType" name="modalPackageType" class="form-control" placeholder="Custom" value="custom" readonly/>
                            </div>
                            <div class="col-12 col-md-6 mb-4">
                                <label for="modal_agenName" class="form-label">Agen</label>
                                <select id="modal_agenName" class="select2 form-select" data-allow-clear="true"
                                    name="user_id" required>
                                    @foreach ( $allagens as $agen )
                                    <option value="{{ $agen->id }}">{{ $agen->username }}</option>
                                    @endforeach
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
                        <div class="row mb-4">

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

        // Form Booking custom Package
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('customModalForm');

            form.addEventListener('submit', function(e) {
            // Pastikan nilai package type di-set ke 'custom'
            const packageTypeInput = document.getElementById('custom_packageType');
            packageTypeInput.value = 'custom';
            });
        });
    </script>
@endsection
