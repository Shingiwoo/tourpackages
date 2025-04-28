@extends('admin.admin_dashboard')
@section('admin')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="mb-6 card">
                <div class="card-body">
                    <h4 class="mb-2 card-title">Add New Expense</h4>
                    <p class="card-text">Silakan pilih akun dan masukkan jumlah untuk membuat jurnal biaya.</p>

                    <form id="addAccountForm" method="POST" action="{{ route('expense.store') }}">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div id="expenseItemsContainer">
                            <div class="mb-3 border rounded expense-item d-flex position-relative pe-0">
                                <div class="p-6 row w-100">
                                    <div class="row">
                                        <input type="hidden" name="expenses[0][BookingId]" value="{{ $booking->id ?? '' }}">
                                        <div class="mb-4 col-12 col-md-4">
                                            <label for="bs-datepicker-autoclose-0" class="form-label">Date</label>
                                            <input type="text" id="bs-datepicker-autoclose-0" placeholder="MM/DD/YYYY"
                                                class="form-control datepicker" name="expenses[0][Date]" required />
                                        </div>
                                        <div class="mb-4 col-12 col-md-4">
                                            <label class="form-label">Account Name</label>
                                            <select required name="expenses[0][AccountId]" class="select form-select">
                                                <option value="">Select Account</option>
                                                @foreach ($accounts as $akun)
                                                    <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-4 col-12 col-md-4">
                                            <label class="form-label">Cost</label>
                                            <input type="text" class="form-control numeral-mask" name="expenses[0][Amount]" placeholder="500,000" required />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-4 col-12">
                                            <label class="form-label">Description</label>
                                            <textarea name="expenses[0][ExpenDescript]" class="form-control" rows="3" maxlength="255" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-1 d-flex flex-column align-items-center justify-content-between border-start">
                                    <i class="cursor-pointer ti ti-x ti-lg remove-item"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 row">
                            <div class="col-12">
                                <button type="button" class="btn btn-sm btn-primary waves-effect waves-light" data-repeater-create>
                                    <i class="ti ti-plus ti-14px me-1_5"></i>Add Item
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center col-12 demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-4">Save</button>
                            <a href="{{ route('all.expenses') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addItemButton = document.querySelector('[data-repeater-create]');
            const expenseItemsContainer = document.getElementById('expenseItemsContainer');
            const originalItem = expenseItemsContainer.querySelector('.expense-item');
            const bookingId = "{{ $booking->id ?? '' }}";

            let itemCount = expenseItemsContainer.querySelectorAll('.expense-item').length;

            // Fungsi untuk menginisialisasi datepicker
            function initDatepicker() {
                const datepickerInputs = document.querySelectorAll('.datepicker');
                datepickerInputs.forEach((input, index) => {
                    // Pastikan ID unik untuk setiap datepicker
                    input.id = `bs-datepicker-autoclose-${index}`;
                    // Hancurkan instance datepicker sebelumnya jika ada
                    if ($(input).data('datepicker')) {
                        $(input).datepicker('destroy');
                    }
                    // Inisialisasi datepicker
                    $(input).datepicker({
                        autoclose: true,
                        format: 'mm/dd/yyyy'
                    });
                });
            }

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

            // Fungsi untuk menambahkan event listener tombol hapus
            function addRemoveItemListeners() {
                const removeItemButtons = document.querySelectorAll('.remove-item');
                removeItemButtons.forEach(button => {
                    button.removeEventListener('click', removeItemHandler); // Hindari duplikasi listener
                    button.addEventListener('click', removeItemHandler);
                });
            }

            function removeItemHandler(event) {
                event.target.closest('.expense-item').remove();
                itemCount--;
                // Update ulang ID datepicker untuk menjaga urutan
                initDatepicker();
            }

            // Event listener untuk tombol "Add Item"
            addItemButton.addEventListener('click', function() {
                const newItem = originalItem.cloneNode(true);
                itemCount++;

                // Update nama dan nilai input
                const inputs = newItem.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/expenses\[0\]/, `expenses[${itemCount - 1}]`));
                    }
                    if (input.tagName === 'INPUT' || input.tagName === 'TEXTAREA') {
                        input.value = '';
                    } else if (input.tagName === 'SELECT') {
                        input.selectedIndex = 0;
                    }
                });

                // Set nilai BookingId
                const bookingIdInput = newItem.querySelector(`[name="expenses[${itemCount - 1}][BookingId]"]`);
                if (bookingIdInput) {
                    bookingIdInput.value = bookingId;
                }

                // Pastikan tombol hapus ada
                let removeButton = newItem.querySelector('.remove-item');
                if (!removeButton) {
                    const removeButtonDiv = document.createElement('div');
                    removeButtonDiv.classList.add('p-1', 'd-flex', 'flex-column', 'align-items-center', 'justify-content-between', 'border-start');
                    removeButtonDiv.innerHTML = '<i class="cursor-pointer ti ti-x ti-lg remove-item"></i>';
                    newItem.appendChild(removeButtonDiv);
                }

                // Tambahkan item baru ke container
                expenseItemsContainer.appendChild(newItem);

                // Inisialisasi ulang plugin
                initDatepicker();
                initNumeralMask();
                addRemoveItemListeners();
            });

            // Inisialisasi awal
            initDatepicker();
            initNumeralMask();
            addRemoveItemListeners();
        });
    </script>
@endsection
