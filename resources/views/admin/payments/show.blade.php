@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Content -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <form id="payment-form" action="{{ route('bookings.payments.upload_proof', [$booking->id, $payment->id]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div
                    class="row-gap-4 mb-6 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="d-flex flex-column justify-content-center">
                        <h4 class="mb-1">Payment Proof</h4>
                        <p class="mb-0">Payment Proof Image</p>
                    </div>
                    <div class="flex-wrap gap-4 d-flex align-content-center">
                        <div class="gap-4 d-flex">
                            <a href="{{ route('payments.index', [$booking->id, $payment->id]) }}" type="button"
                                class="btn btn-label-secondary">Back</a>
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
                                        <h5 class="text-uppercase text-success">{{ $booking->bookingList->agen->username }}
                                        </h5>
                                    </div>
                                    <div class="col">
                                        <label class="form-label" for="type-trip">Trip Type</label>
                                        <h5 class="text-uppercase text-warning">{{ $booking->type }}</h5>
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-2 row">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="total-price">Ammount</label>
                                        <h5 class="text-success">Rp {{ number_format($payment->ammount, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="booking-status">Payment Type</label>
                                        <h5 class="text-warning"><span class="text-uppercase">{{ $payment->type }}</span>
                                            @if ($payment->type == 'dp')
                                                <span class="badge bg-label-warning">ke -
                                                    {{ $payment->dp_installment }}</span>
                                            @endif
                                        </h5>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="booking-status">Payment Method</label>
                                        <h5 class="text-danger text-uppercase">{{ $payment->method }}</h5>
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
                                <h5 class="mb-0 card-title">Payment Proof</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="gap-6 d-flex align-items-start align-items-sm-center">
                                            <img id="showImage" style="width: 150px; height: 150px;"
                                                src="{{ asset('assets/img/avatars/no_image.jpg') }}" alt="user-avatar"
                                                class="mb-1 rounded d-block w-px-150 h-px-200" />
                                            <div class="button-wrapper">
                                                <label for="proof" class="mb-4 btn btn-primary me-3" tabindex="0">
                                                    <span class="d-none d-sm-block">Upload Proof</span>
                                                    <i class="ti ti-upload d-block d-sm-none"></i>
                                                    <input type="file" id="proof" name="payment_proof"
                                                        class="account-file-input" hidden
                                                        accept="image/png, image/jpeg, image/gif, image/jpg" />
                                                </label>
                                                <div class="mt-2">Allowed JPG, GIF or PNG. Max size of 800K</div>
                                            </div>
                                        </div>
                                        @if ($payment->proof_of_transfer)
                                            <div class="mt-6">
                                                <h5 class="mb-1">Current Payment Proof:</h5>
                                                <a href="{{ route('payment.proof.show', ['booking' => $booking->id, 'filename' => basename($payment->proof_of_transfer)]) }}"
                                                    target="_blank">
                                                    <img src="{{ route('payment.proof.show', ['booking' => $booking->id, 'filename' => basename($payment->proof_of_transfer)]) }}"
                                                        alt="Payment Proof" style="max-width: 200px; max-height: 400px;"
                                                        class="img-thumbnail">
                                                </a>
                                                <p class="mt-2 form-text text-muted">
                                                    {{ basename($payment->proof_of_transfer) }}
                                                </p>
                                            </div>
                                        @elseif (!$payment->proof_of_transfer && $payment->status === 'waiting' && $payment->method !== 'tunai')
                                            <p class="mt-2 text-warning">Bukti transfer belum diupload.</p>
                                        @elseif ($payment->status === 'terbayar')
                                            <p class="mt-2 text-success">Pembayaran telah dikonfirmasi.</p>
                                        @elseif ($payment->status === 'cancel')
                                            <p class="mt-2 text-danger">Pembayaran dibatalkan.</p>
                                        @endif

                                        @error('payment_proof')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Form Paymet Card -->
                    </div>
                    <!-- /Second column -->
                </div>
            </form>
            @if ($payment->payment_at === null && $payment->proof_of_transfer != null && $payment->status === 'waiting')
                <div class="mt-2">
                    <form action="{{ route('bookings.payments.confirm', [$booking->id, $payment->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success"><i class="ti ti-check"></i> Konfirmasi Pembayaran
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
    <!-- / Content -->

    <script type="text/javascript">
        $(document).ready(function() {
            $('#proof').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0'])
            });
        });
    </script>
@endsection
