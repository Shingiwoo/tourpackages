@extends('admin.admin_dashboard')
@section('admin')
    <!-- Content -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="mb-6 card">
                <div class="card-body">
                    <h4 class="mb-2 card-title">Add New Expense</h4>
                    <p class="card-text">Silakan pilih akun dan masukkan jumlah untuk membuat jurnal biaya.</p>

                    <form id="addAccountForm" method="POST" action="{{ route('expense.store') }}" class="source-item">
                        @csrf
                        <div id="expenseItemsContainer" data-repeater-list="expenses">
                            <div class="mb-3 border rounded expense-item d-flex position-relative pe-0" data-repeater-item>
                                <div class="p-6 row w-100">
                                    <div class="row">
                                        <input type="hidden" name="expenses[0][BookingId]"
                                            value="{{ $booking->id ?? '' }}">
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
                                            <input type="text" class="form-control numeral-mask"
                                                name="expenses[0][Amount]" placeholder="500,000" required />
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
                                <button type="button" class="btn btn-sm btn-primary waves-effect waves-light"
                                    data-repeater-create>
                                    <i class="ti ti-plus ti-14px me-1_5"></i>Add Item
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center col-12 demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-4">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--/ Content End -->
    @endsection
