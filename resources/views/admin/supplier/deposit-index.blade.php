@extends('admin.admin_dashboard')
@section('admin')
    <!-- Content -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card">
                <div class="pt-0 card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="card-header flex-column flex-md-row">
                            <div class="text-center head-label">
                                <h5 class="mb-0 card-title">Supplier Deposit List</h5>
                            </div>
                            <div class="pt-6 text-center dt-action-buttons pt-md-0">
                                <div class="btn-group">
                                </div>
                                @if (Auth::user()->can('accounting.add'))
                                    <button type="button"
                                        class="btn btn-secondary create-new btn-primary waves-effect waves-light"
                                        data-bs-toggle="modal" data-bs-target="#addSupplierDepositModal">
                                        <span><i class="ti ti-plus me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Add Supplier</span>
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-datatable text-nowrap">                            
                            <div class="table-responsive">
                                <table id="example" class="table datatables-ajax">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-content-center text-primary">#</th>
                                            <th class="text-center align-content-center text-primary">Supplier Name</th>
                                            <th class="text-center align-content-center text-primary">Deposit Amount</th>
                                            <th class="text-center align-content-center text-primary">Deposit Date</th>
                                            <th class="text-center align-content-center text-primary">Booking Code</th>
                                            <th class="text-center align-content-center text-primary">Remaining Amount</th>
                                            <th class="text-center align-content-center text-primary">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($deposits as $index => $deposit)
                                            <tr>
                                                <td class="text-center align-content-center">{{ $index + 1 }}</td>
                                                <td class="text-center align-content-center">{{ $deposit->supplier_name }}</td>
                                                <td class="text-center align-content-center"><span
                                                        class="badge bg-primary rounded-pill">Rp
                                                        {{ number_format($deposit->amount, 0, ',', '.') }}</span></td>
                                                <td class="text-center align-content-center">
                                                    {{ $deposit->date->format('d-m-Y') }}</td>
                                                <td class="text-center align-content-center">{{ $deposit->booking->code_booking ?? 'Tidak Terhubung'}}
                                                </td>
                                                <td class="text-center align-content-center">
                                                    <span class="badge bg-info rounded-pill">Rp
                                                        {{ number_format($deposit->remaining_amount, 0, ',', '.') }}</span>
                                                </td>
                                                <td class="text-center align-content-center">
                                                    <!-- Icon Dropdown -->
                                                    <div class="col-sm-3 col-sm-6 col-sm-12">
                                                        <div class="btn-group">
                                                            <button type="button"
                                                                class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                @if (Auth::user()->can('accounting.edit'))
                                                                    <li>
                                                                        <a class="dropdown-item button" href="{{ route('edit.supplier-deposit', $deposit->id) }}">
                                                                            <i class="ti ti-pencil me-1"></i> Edit
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if (Auth::user()->can('accounting.delete'))
                                                                    <li><a href="javascript:void(0)"
                                                                            class="dropdown-item text-danger delete-confirm"
                                                                            data-id="{{ $deposit->id }}"
                                                                            data-url="{{ route('delete.supplier-deposit', $deposit->id) }}">
                                                                            <i class="ti ti-trash"></i> Delete
                                                                        </a></li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <!--/ Icon Dropdown -->
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Deposit Modal -->
    <div class="modal fade" id="addSupplierDepositModal" tabindex="-1" aria-labelledby="addSupplierDepositModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSupplierDepositModalLabel">Add Supplier Deposit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addSupplierDepositForm" method="POST" action="{{ route('store.supplier-deposits') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-4 col-12 col-md-4">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" id="date" placeholder="MM/DD/YYYY" class="form-control"
                                    name="Date" required />
                            </div>
                            <div class="mb-4 col-12 col-md-4">
                                <label for="supplierName" class="form-label">Supplier</label>
                                <select class="select2 form-select" id="supplierName" name="supplierName" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4 col-12 col-md-4">
                                <label for="amount" class="form-label">Deposit Amount</label>
                                <input type="text" class="form-control numeral-mask" id="amount" name="amount"
                                    required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-12">
                                <div class="mb-2 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="BookingTrueAdd">
                                    <label class="form-check-label" for="BookingTrueAdd">Enable this if you need to add a
                                        supplier to the booking order.</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-12 col-md-6">
                                <label for="bookingIdAdd" class="form-label">Booking Kode</label>
                                <select class="select2 form-select" id="bookingIdAdd" name="booking_id">
                                    <option value="">Select Booking Code*</option>
                                    @foreach ($bookings as $booking)
                                        <option value="{{ $booking->id }}">{{ $booking->code_booking }}</option>
                                    @endforeach
                                </select>
                                <small class="text-warning">*add booking code if needed</small>
                            </div>
                            <div class="mb-4 col-12 col-md-6">
                                <label for="remainingAmountAdd" class="form-label">Remaining Amount</label>
                                <input type="text" class="form-control numeral-mask" id="remainingAmountAdd"
                                    name="remaining_amount" required>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 modal-footer">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingTrueCheckboxAdd = document.getElementById('BookingTrueAdd');
            const bookingIdColumnAdd = document.querySelector('label[for="bookingIdAdd"]')?.closest('.col-md-6');
            const bookingIdSelectAdd = document.getElementById('bookingIdAdd');
            const remainingAmountColumnAdd = document.querySelector('label[for="remainingAmountAdd"]')?.closest(
                '.col-md-6');
            const remainingAmountInputAdd = document.getElementById('remainingAmountAdd');

            function toggleBookingFieldsAdd() {
                if (bookingTrueCheckboxAdd.checked) {
                    if (bookingIdColumnAdd) {
                        bookingIdColumnAdd.style.display = 'block';
                    }
                    if (remainingAmountColumnAdd) {
                        remainingAmountColumnAdd.style.display = 'block';
                    }
                    if (bookingIdSelectAdd) {
                        bookingIdSelectAdd.required = true;
                    }
                    if (remainingAmountInputAdd) {
                        remainingAmountInputAdd.required = true;
                    }
                } else {
                    if (bookingIdColumnAdd) {
                        bookingIdColumnAdd.style.display = 'none';
                    }
                    if (remainingAmountColumnAdd) {
                        remainingAmountColumnAdd.style.display = 'none';
                    }
                    if (bookingIdSelectAdd) {
                        bookingIdSelectAdd.required = false;
                    }
                    if (remainingAmountInputAdd) {
                        remainingAmountInputAdd.required = false;
                    }
                }
            }

            // Initial state on page load for Add Modal
            toggleBookingFieldsAdd();

            // Toggle visibility when the checkbox state changes for Add Modal
            bookingTrueCheckboxAdd.addEventListener('change', toggleBookingFieldsAdd);            

            // Fungsi untuk menginisialisasi numeral mask
            function initNumeralMask() {
                const numeralMaskInputs = document.querySelectorAll('.numeral-mask');
                numeralMaskInputs.forEach(input => {
                    // Hancurkan instance Cleave sebelumnya jika ada
                    if (input.cleave) {
                        input.cleave.destroy();
                    }
                    // Inisialisasi Cleave
                    new Cleave(input, {
                        numeral: true,
                        numeralThousandsGroupStyle: 'thousand',
                        delimiter: ',',
                        numeralDecimalMark: '.'
                    });
                });
            }
            
            initNumeralMask();
        });
    </script>
@endsection
