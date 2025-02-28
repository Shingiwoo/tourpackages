@extends('admin.admin_dashboard')
@section('admin')
    <div class="container-xxl flex-grow-1 container-p-y">

        <form action="{{ route('booking.update', $booking->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Edit Booking</h4>
                    <p class="mb-0">Change Booking Data and Price</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <div class="d-flex gap-4">
                        <a href="{{ route('all.bookings') }}" type="button" class="btn btn-label-primary">Back</a>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>

            <div class="row">
                <!-- First column-->
                <div class="col-12 col-lg-6">
                    <!-- Product Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Booking Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-6">
                                <label class="form-label" for="booking-code">Code</label>
                                <h5 class="text-uppercase text-info">{{ $booking->code_booking }}</h5>
                            </div>
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="agenName">Name Agen</label>
                                    <h5 class="text-uppercase text-success">{{ $booking->bookingList->agen->username }}</h5>
                                </div>
                                <div class="col">
                                    <label class="form-label" for="type-trip">Trip Type</label>
                                    <h5 class="text-uppercase text-warning">{{ $booking->type }}</h5>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="status_booking">Booking Status</label>
                                    <select required id="status_booking" name="bookingstatus" class="select2 form-select"
                                        data-allow-clear="true">
                                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>
                                            PENDING</option>
                                        <option value="booked" {{ $booking->status == 'booked' ? 'selected' : '' }}>BOOKED
                                        </option>
                                        <option value="paid" {{ $booking->status == 'paid' ? 'selected' : '' }}>PAID
                                        </option>
                                        <option value="finished" {{ $booking->status == 'finished' ? 'selected' : '' }}>
                                            FINISHED</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="client-name">Client Name</label>
                                    <input type="text" class="form-control" id="client-name" name="ClientName"
                                        aria-label="Client Name" value="{{ $booking->name }}" />
                                </div>
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="basic-icon-default-message">Message</label>
                                <div class="input-group input-group-merge">
                                  <span id="basic-icon-default-message2" class="input-group-text"><i class="ti ti-message-dots"></i></span>
                                  <textarea id="basic-icon-default-message" class="form-control" name="noteData"  placeholder="Tulis Pesan tambahan jika ada" aria-label="Tulis Pesan tambahan jika ada" aria-describedby="basic-icon-default-message2"></textarea>
                                </div>
                              </div>
                        </div>
                    </div>
                    <!-- /Product Information -->
                </div>

                <!-- Second column -->
                <div class="col-12 col-lg-6">
                    <!-- Pricing Card -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Booking Detail</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="startdate-booking">Start Date</label>
                                    <input type="date" class="form-control" id="startdate-booking" name="startDate"
                                        value="{{ $booking->start_date }}" />
                                </div>
                                <div class="col">
                                    <label class="form-label" for="enddate-booking">End Date</label>
                                    <input type="date" class="form-control" id="enddate-booking" name="endDate"
                                        value="{{ $booking->end_date }}" />
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12 col-md-6 mb-4">
                                    <label for="modalStartTime" class="form-label">Start Time</label>
                                    <input type="text" id="modalStartTime" class="form-control" name="startTime" value="{{ $booking->start_trip }}"/>
                                </div>
                                <div class="col-12 col-md-6 mb-4">
                                    <label for="modalEndTime" class="form-label">End Time</label>
                                    <input type="text" id="modalEndTime" class="form-control" name="endTime" value="{{ $booking->end_trip }}"/>
                                </div>
                            </div>
                            <!-- Base Price -->
                            <hr>
                            <div class="row">
                                <div class="col-12 col-md-4 mb-6">
                                    <label class="form-label" for="user-total">Total User</label>
                                    <input type="number" class="form-control" id="user-total" name="totalUser"
                                        aria-label="Price Total" value="{{ $booking->total_user }}" />
                                </div>
                                <div class="col-12 col-md-4 mb-6">
                                    <label class="form-label" for="price-perpax">Price Perpax</label>
                                    <input type="text" class="form-control numeral-mask" id="price-perpax"
                                        name="pricePerPerson" aria-label="Price Perpax"
                                        value="{{ $booking->price_person }}" />
                                </div>
                                <div class="col-12 col-md-4 mb-6">
                                    <label class="form-label" for="price-total">Total Price</label>
                                    <input type="text" class="form-control numeral-mask2" id="price-total"
                                        name="totalPrice" aria-label="Price Total"
                                        value="{{ $booking->total_price }}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 mb-6">
                                    <label class="form-label" for="down-payment">Down Payment</label>
                                    <input type="text" class="form-control numeral-mask3" id="down-payment"
                                        name="downPayment" aria-label="Down Payment"
                                        value="{{ $booking->down_paymet }}" />
                                </div>
                                <div class="col-12 col-md-6 mb-6">
                                    <label class="form-label" for="remaining-cost">Remaining Cost</label>
                                    <input type="text" class="form-control numeral-mask4" id="remaining-cost"
                                        name="remainingCosts" aria-label="Remaining Cost"
                                        value="{{ $booking->remaining_costs }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Pricing Card -->
                </div>
                <!-- /Second column -->
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil elemen input
            const totalUserInput = document.getElementById("user-total");
            const pricePerPersonInput = document.getElementById("price-perpax");
            const totalPriceInput = document.getElementById("price-total");
            const downPaymentInput = document.getElementById("down-payment");
            const remainingCostInput = document.getElementById("remaining-cost");

            function updatePrices() {
                // Ambil nilai dari input
                let totalUser = parseInt(totalUserInput.value) || 0;
                let pricePerPerson = parseInt(pricePerPersonInput.value.replace(/\D/g, "")) || 0;

                // Hitung nilai baru
                let totalPrice = totalUser * pricePerPerson;
                let downPayment = Math.round(totalPrice * 0.3);
                let remainingCosts = totalPrice - downPayment;

                // Format angka sebagai ribuan
                totalPriceInput.value = totalPrice.toLocaleString("id-ID");
                downPaymentInput.value = downPayment.toLocaleString("id-ID");
                remainingCostInput.value = remainingCosts.toLocaleString("id-ID");
            }

            // Tambahkan event listener untuk perubahan nilai
            totalUserInput.addEventListener("input", updatePrices);
            pricePerPersonInput.addEventListener("input", updatePrices);

            // Jalankan updatePrices saat halaman dimuat untuk memastikan nilai sudah benar
            updatePrices();

            // Inisialisasi flatpickr
            flatpickr("#modalStartTime", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
            flatpickr("#modalEndTime", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
        });
    </script>
@endsection
