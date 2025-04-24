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
                                        <div class="mb-4 col-12 col-md-6">
                                            <label class="form-label">Account Name</label>
                                            <select required name="expenses[0][AccountId]" class="select form-select">
                                                <option value="">Select Account</option>
                                                @foreach ($accounts as $akun)
                                                    <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-4 col-12 col-md-6">
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
                                    <i class="cursor-pointer ti ti-x ti-lg" data-repeater-delete></i>
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
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addItemButton = document.querySelector('[data-repeater-create]');
            const expenseItemsContainer = document.getElementById('expenseItemsContainer');
            const originalItem = expenseItemsContainer.querySelector('.expense-item');
    
            let itemCount = expenseItemsContainer.querySelectorAll('.expense-item').length;
    
            // Fungsi untuk menginisialisasi numeral mask (asumsi Anda punya fungsi ini)
            function initNumeralMask() {
                const numeralMaskInputs = document.querySelectorAll('.numeral-mask');
                numeralMaskInputs.forEach(input => {
                    new Cleave(input, {
                        numeral: true,
                        numeralThousandsGroupStyle: 'thousand',
                        delimiter: ',',
                        numeralDecimalMark: '.',
                        onValueChanged: function (e) {
                            // Anda mungkin perlu melakukan sesuatu dengan nilai yang diformat di sini
                        }
                    });
                });
            }
    
            addItemButton.addEventListener('click', function() {
                const newItem = originalItem.cloneNode(true);
                itemCount++;
    
                // Update the index for all input elements in the new item
                const inputs = newItem.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/expenses\[0\]/, `expenses[${itemCount - 1}]`));
                    }
                    if (input.tagName === 'INPUT' || input.tagName === 'TEXTAREA') {
                        input.value = ''; // Clear the value for new items
                    } else if (input.tagName === 'SELECT') {
                        input.selectedIndex = 0; // Reset the selected option
                    }
                });
    
                // Check if the cloned item already has a remove button
                if (!newItem.querySelector('.remove-item')) {
                    // Add a remove button if it doesn't exist
                    const removeButton = document.createElement('div');
                    removeButton.classList.add('p-1', 'd-flex', 'flex-column', 'align-items-center', 'justify-content-between', 'border-start');
                    removeButton.innerHTML = '<i class="cursor-pointer ti ti-x ti-lg remove-item"></i>';
                    newItem.appendChild(removeButton);
                }
    
                // Add the new item to the container
                expenseItemsContainer.appendChild(newItem);
    
                // Re-initialize event listeners for remove buttons
                addRemoveItemListeners();
    
                // Initialize numeral mask for the new item
                initNumeralMask();
            });
    
            function addRemoveItemListeners() {
                const removeItemButtons = document.querySelectorAll('.remove-item');
                removeItemButtons.forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.target.closest('.expense-item').remove();
                        itemCount--;
                    });
                });
            }
    
            // Initial setup: Add remove button listeners and initialize numeral mask for existing items
            addRemoveItemListeners();
            initNumeralMask();
        });
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addItemButton = document.querySelector('[data-repeater-create]');
            const expenseItemsContainer = document.getElementById('expenseItemsContainer');
            const originalItem = expenseItemsContainer.querySelector('.expense-item');
            const bookingId = "{{ $booking->id ?? '' }}";
    
            let itemCount = expenseItemsContainer.querySelectorAll('.expense-item').length;
    
            function initNumeralMask() {
                const numeralMaskInputs = document.querySelectorAll('.numeral-mask');
                numeralMaskInputs.forEach(input => {
                    new Cleave(input, {
                        numeral: true,
                        numeralThousandsGroupStyle: 'thousand',
                        delimiter: ',',
                        numeralDecimalMark: '.',
                        onValueChanged: function (e) {
                            // Anda mungkin perlu melakukan sesuatu dengan nilai yang diformat di sini
                        }
                    });
                });
            }
    
            addItemButton.addEventListener('click', function() {
                const newItem = originalItem.cloneNode(true);
                itemCount++;
    
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
    
                // Secara eksplisit set nilai BookingId pada item baru
                const bookingIdInput = newItem.querySelector('[name^="expenses[' + (itemCount - 1) + '][BookingId]"]');
                if (bookingIdInput) {
                    bookingIdInput.value = bookingId;
                }
    
                if (!newItem.querySelector('.remove-item')) {
                    const removeButton = document.createElement('div');
                    removeButton.classList.add('p-1', 'd-flex', 'flex-column', 'align-items-center', 'justify-content-between', 'border-start');
                    removeButton.innerHTML = '<i class="cursor-pointer ti ti-x ti-lg remove-item"></i>';
                    newItem.appendChild(removeButton);
                }
    
                expenseItemsContainer.appendChild(newItem);
                addRemoveItemListeners();
                initNumeralMask();
            });
    
            function addRemoveItemListeners() {
                const removeItemButtons = document.querySelectorAll('.remove-item');
                removeItemButtons.forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.target.closest('.expense-item').remove();
                        itemCount--;
                    });
                });
            }
    
            addRemoveItemListeners();
            initNumeralMask();
        });
    </script>
@endsection
