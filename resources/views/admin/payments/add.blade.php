@extends('admin.admin_dashboard')
@section('admin')
    <!-- Content -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <form id="payment-form" action="{{ route('payments.store', $booking->id) }}" method="POST">
                @csrf
            <div class="row-gap-4 mb-6 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="d-flex flex-column justify-content-center">
                        <h4 class="mb-1">Add Payment</h4>
                        <p class="mb-0">Add Payment for Booking</p>
                    </div>
                    <div class="flex-wrap gap-4 d-flex align-content-center">
                        <div class="gap-4 d-flex">
                            <a href="{{ route('all.bookings') }}" type="button" class="btn btn-label-secondary">Back</a>
                        </div>
                        <div class="gap-4 d-flex">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- First column -->
                    <div class="col-12 col-lg-6">
                        <!-- Booking Information -->
                        <div class="mb-4 card">
                            <div class="card-header">
                                <h5 class="mb-0 card-tile">Booking Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-1">
                                    <label class="form-label" for="booking-code">Code</label>
                                    <h5 class="text-uppercase text-info">{{ $booking->code_booking }}</h5>
                                </div>
                                <div class="mb-1 row">
                                    <div class="col">
                                        <label class="form-label" for="agenName">Name Agen</label>
                                        <h5 class="text-uppercase text-success">{{ $booking->bookingList->agen->username }}</h5>
                                    </div>
                                    <div class="col">
                                        <label class="form-label" for="type-trip">Trip Type</label>
                                        <h5 class="text-uppercase text-warning">{{ $booking->type }}</h5>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="total-price">Total Price</label>
                                        <h5 class="text-success">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</h5>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="booking-status">Down Payment</label>
                                        <h5 class="text-warning">Rp {{ number_format($booking->down_paymet, 0, ',', '.') }}</h5>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="booking-status">Remaining Cost</label>
                                        <h5 class="text-danger">Rp {{ number_format($booking->remaining_costs, 0, ',', '.') }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Booking Information -->
                    </div>

                    <!-- Second column -->
                    <div class="col-12 col-lg-6">
                        <!-- Form Paymet Card -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">Payment Create</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-1 row">
                                    <div class="mb-2 col-12 col-md-6">
                                        <label class="form-label" for="type">Type</label>
                                        <select name="type" class="form-select" id="type-payment">
                                            <option value="">Select Type</option>
                                            <option value="dp">Down Payment</option>
                                            <option value="pelunasan">Pelunasan</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="payment-method">Payment Method</label>
                                        <select name="method" class="form-select" id="payment-method">
                                            <option value="">Select Method</option>
                                            <option value="tunai">Tunai</option>
                                            <option value="transfer">Transfer</option>
                                            <option value="virtual_account">Virtual Account</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <div class="mb-2 col-12 col-md-6">
                                        <label class="form-label" for="dp-to">DP to</label>
                                        <input type="number" class="form-control" id="dp-to" name="dp_installment"
                                            aria-label="DP to" min="1" max="3"/>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="payment-duedate">Payment Due Date</label>
                                        <input type="date" class="form-control" id="payment-duedate" name="payment_due_date"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-12 col-md-6">
                                        <label class="form-label" for="payment-ammount">Ammount</label>
                                        <input type="text" class="form-control numeral-mask" id="payment-ammount" name="ammount" aria-label="Ammount"/>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="payment-status">Status</label>
                                        <input type="text" class="form-control" id="payment-status" name="status"
                                        aria-label="Status" value="waiting"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Form Paymet Card -->
                    </div>
                    <!-- /Second column -->
                </div>
            </form>
        </div>
    </div>
    <!-- / Content -->
@endsection
