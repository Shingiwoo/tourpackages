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
                                <div class="mb-3 border rounded expense-item d-flex position-relative pe-0">
                                    <div class="p-6 row w-100">
                                        <div class="row">
                                            <input type="hidden" name="BookingId" value="{{ $expense->booking_id }}">

                                            <div class="mb-4 col-12 col-md-4">
                                                <label for="bs-datepicker-autoclose" class="form-label">Date</label>
                                                <input type="text" id="bs-datepicker-autoclose" placeholder="MM/DD/YYYY" value="{{ $expense->date }}"
                                                    class="form-control" name="Date" required />
                                            </div>
                                            <div class="mb-4 col-12 col-md-4">
                                                <label class="form-label">Account Name</label>
                                                <select required name="AccountId" class="select2 form-select">
                                                    <option value="">Account Name</option>
                                                    @foreach ($accounts as $akun)
                                                        <option value="{{ $akun->id }}"
                                                            {{ $expense->account_id == $akun->id ? 'selected' : '' }}>
                                                            {{ $akun->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-4 col-12 col-md-4">
                                                <label class="form-label">Cost</label>
                                                <input type="text" class="form-control numeral-mask" name="Amount"
                                                    value="{{ $expense->amount }}" required />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-4 col-12">
                                                <label class="form-label">Deskripsi</label>
                                                <textarea name="ExpenDescript" class="form-control" rows="3" maxlength="255" style="height: 30px;">{{ $expense->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="p-1 d-flex flex-column align-items-center justify-content-between border-start">
                                        <i class="cursor-pointer ti ti-x ti-lg remove-item-btn"></i>
                                    </div>
                                </div>
                                <div class="mb-3 border rounded expense-item d-flex position-relative pe-0">
                                    <div class="p-6 row w-100">
                                        <div class="row">
                                            <div class="mb-4 col-12 col-md-6">
                                                <label class="text-warning"><b>Old Code Booking :</b> <span class="text-dark"><strong>{{ $expense->booking->code_booking }} - {{ $expense->booking->package_name }}</strong></span></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-4 col-12 col-md-6">
                                                <label class="form-label text-warning" for="NewBookingId"><b>New Code Booking</b></label>
                                                <select name="NewBookingId" class="select form-select">
                                                    <option value="">-- Tidak Dipindah --</option>
                                                    @foreach ($bookings as $booking)
                                                        <option value="{{ $booking->id }}"
                                                        {{ $booking->id == $expense->booking_id ? 'selected' : '' }}>
                                                        {{ $booking->code_booking }} - {{ $booking->package_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-warning">* Jika ingin memindahkan pengeluaran ini ke booking lain</small>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="flex-wrap gap-4 d-flex align-content-center">
                                    <button type="submit"
                                        class="mt-2 btn btn-primary waves-effect waves-light ">Update</button>
                                    <div class="gap-4 d-flex">
                                        <a href="{{ route('all.expenses') }}" type="button"
                                            class="mt-2 btn btn-secondary waves-effect waves-light">Back</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
