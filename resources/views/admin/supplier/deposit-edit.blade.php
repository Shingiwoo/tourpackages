@extends('admin.admin_dashboard')
@section('admin')
    <!-- Content -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <form id="editSupplierDepositForm" action="{{ route('update.supplier-deposit', $deposit->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                        <div class="d-flex flex-column justify-content-center">
                            <h4 class="mb-1">Update Supplier Deposit</h4>
                        </div>
                        <div class="d-flex align-content-center flex-wrap gap-4">
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-4 col-12 col-md-4">
                            <label for="edit_date" class="form-label">Date</label>
                            <input type="date" id="edit_date" placeholder="MM/DD/YYYY" class="form-control"
                                name="Date" value="{{ $deposit->date }}" required />
                        </div>
                        <div class="mb-4 col-12 col-md-4">
                            <label for="edit_supplierName" class="form-label">Supplier</label>
                            <select class="select2 form-select" id="edit_supplierName" name="supplierName" required>
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->name }}"  {{ $deposit->supplier_name == $supplier->name ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4 col-12 col-md-4">
                            <label for="edit_amount" class="form-label">Deposit Amount</label>
                            <input type="text" class="form-control numeral-mask" id="edit_amount" name="amount"
                                value="{{ $deposit->amount }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-4 col-12">
                            <div class="mb-2 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="BookingTrueEdit">
                                <label class="form-check-label" for="BookingTrueEdit">Enable this if you need to add a
                                    supplier to the booking order.</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-4 col-12 col-md-6">
                            <label for="edit_bookingId" class="form-label">Booking Kode</label>
                            <select class="select2 form-select" id="edit_bookingId" name="booking_id">
                                <option value="">Select Booking Code*</option>
                                @foreach ($bookings as $booking)
                                    <option value="{{ $booking->id }}" {{ $deposit->booking_id == $booking->id ? 'selected' : '' }}>{{ $booking->code_booking }}</option>
                                @endforeach
                            </select>
                            <small class="text-warning">*add booking code if needed</small>
                        </div>
                        <div class="mb-4 col-12 col-md-6">
                            <label for="edit_remainingAmount" class="form-label">Remaining Amount</label>
                            <input type="text" class="form-control numeral-mask" id="edit_remainingAmount"
                                name="remaining_amount" value="{{ $deposit->remaining_amount }}" required>
                        </div>
                    </div>
                </div>
                <div class="mt-4 modal-footer">
                    <button type="submit" class="btn btn-primary" style="margin-right: 10px">Update changes</button>
                    <a href="{{ route('all.supplier-deposits') }}" type="button" class="btn btn-secondary" >Back</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingTrueCheckboxEdit = document.getElementById('BookingTrueEdit');
            const bookingIdColumnEdit = document.querySelector('label[for="edit_bookingId"]')?.closest('.col-md-6');
            const bookingIdSelectEdit = document.getElementById('edit_bookingId');
            const remainingAmountColumnEdit = document.querySelector('label[for="edit_remainingAmount"]')?.closest(
                '.col-md-6');
            const remainingAmountInputEdit = document.getElementById('edit_remainingAmount');

            function toggleBookingFieldsEdit() {
                if (bookingTrueCheckboxEdit.checked) {
                    if (bookingIdColumnEdit) {
                        bookingIdColumnEdit.style.display = 'block';
                    }
                    if (remainingAmountColumnEdit) {
                        remainingAmountColumnEdit.style.display = 'block';
                    }
                    if (bookingIdSelectEdit) {
                        bookingIdSelectEdit.required = true;
                    }
                    if (remainingAmountInputEdit) {
                        remainingAmountInputEdit.required = true;
                    }
                } else {
                    if (bookingIdColumnEdit) {
                        bookingIdColumnEdit.style.display = 'none';
                    }
                    if (remainingAmountColumnEdit) {
                        remainingAmountColumnEdit.style.display = 'none';
                    }
                    if (bookingIdSelectEdit) {
                        bookingIdSelectEdit.required = false;
                    }
                    if (remainingAmountInputEdit) {
                        remainingAmountInputEdit.required = false;
                    }
                }
            }

            // Initial state on page load for Edit Modal (akan di-override saat modal ditampilkan)
            toggleBookingFieldsEdit();

            // Toggle visibility when the checkbox state changes for Edit Modal
            bookingTrueCheckboxEdit.addEventListener('change', toggleBookingFieldsEdit);

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
