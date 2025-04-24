@extends('admin.admin_dashboard')
@section('admin')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="mb-4 col-lg-12 order-0">
                    <div class="card">
                        <h5 class="card-header">Edit Expense</h5>
                        <div class="card-body">
                            <form method="POST" action="{{ route('update.expense', $expense->id) }}">
                                @csrf
                                @method('PUT')
                                <div id="expenseItemsContainer">
                                    <div class="mb-3 border rounded expense-item d-flex position-relative pe-0">
                                        <div class="p-6 row w-100">
                                            <div class="row">
                                                <input type="hidden" name="expenses[0][BookingId]" value="{{ $expense->booking_id }}">
                                                <div class="mb-4 col-12 col-md-6">
                                                    <label class="form-label">Account Name</label>
                                                    <select required name="expenses[0][AccountId]"
                                                        class="select2 form-select">
                                                        <option value="">Account Name</option>
                                                        @foreach ($accounts as $akun)
                                                            <option value="{{ $akun->id }}" {{ $expense->account_id == $akun->id  ? 'selected' : '' }}>{{ $akun->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-4 col-12 col-md-6">
                                                    <label class="form-label">Cost</label>
                                                    <input type="text" class="form-control numeral-mask"
                                                        name="expenses[0][Amount]" value="{{ $expense->amount }}" required />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mb-4 col-12">
                                                    <label class="form-label">Deskripsi</label>
                                                    <textarea name="expenses[0][ExpenDescript]" class="form-control" rows="3" maxlength="255" style="height: 30px;">{{ $expense->description }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="p-1 d-flex flex-column align-items-center justify-content-between border-start">
                                            <i class="cursor-pointer ti ti-x ti-lg remove-item-btn"></i>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="mt-2 btn btn-primary waves-effect waves-light ">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
