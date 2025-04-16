<div class="modal-body">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    <div class="text-center mb-6">
        <h4 class="mb-2">Detail Booking</h4>
    </div>
    <div class="row mb-4">
        <div class="col">
            <div class="card-datatable table-responsive text-nowrap">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Code :</th>
                            <td>{{ $booking->code_booking }}</td>
                        </tr>
                        <tr>
                            <th>Type Trip :</th>
                            <td><span
                                class="badge bg-{{ $booking->type === 'oneday' ? 'info' : ($booking->type === 'twoday' ? 'primary' : ($booking->type === 'threeday' ? 'success' : ($booking->type === 'fourday' ? 'danger' : 'secondary'))) }} text-uppercase">
                                {{ $booking->type }}
                            </span></td>
                        </tr>
                        <tr>
                            <th>Status :</th>
                            <td><span
                                class="badge bg-{{ $booking->status === 'pending' ? 'danger' : ($booking->status === 'booked' ? 'info' : ($booking->status === 'paid' ? 'primary' : 'success'))}} bg-glow text-uppercase">{{ $booking->status }}</span></td>
                        </tr>
                        <tr>
                            <th>Client Name :</th>
                            <td><span class="text-capitalize">{{ $booking->name }}</span></td>
                        </tr>
                        <tr>
                            <th>Start Trip :</th>
                            <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>End Trip :</th>
                            <td>{{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row g-6">
        <div class="card-datatable table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="fw-medium mx-2 text-center" style="width: 40%">Detail</th>
                        <th class="fw-medium mx-2 text-center">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th><i class="ti ti-receipt ti-lg mx-2"></i><strong>Biaya
                                Perorang</strong></th>
                        <td class="text-end perperso-cost">Rp
                            {{ number_format($booking->price_person, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-user ti-lg mx-2"></i><strong>Total User</strong>
                        </th>
                        <td class="text-end total-user">{{ $booking->total_user }} orang</td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-receipt ti-lg mx-2"></i><strong>Total Biaya</strong>
                        </th>
                        <td class="text-end total-cost">Rp
                            {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-cash-register ti-lg mx-2"></i><strong>Down
                                Payment</strong></th>
                        <td class="text-end down-payment">Rp
                            {{ number_format($booking->down_paymet, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-cash ti-lg mx-2"></i><strong>Sisa Biaya</strong>
                        </th>
                        <td class="text-end remaining-costs">Rp
                            {{ number_format($booking->remaining_costs, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
            @php
                $kidsCost = $booking->price_person * 0.3;
                $wnaCost = $booking->price_person * 0.44;
            @endphp
            <div class="row mt-4">
                <h6 class="text-warning mb-2">*Keterangan* : </h6>
                <ul>
                    <li class="mb-2">Biaya Anak-anak: <strong class="child-cost">Rp
                        {{ number_format($kidsCost, 0, ',', '.') }}</strong>
                        <br><span class="st-italic fs-6 text-info">Dengan usia 4 - 10 tahun, 11
                            tahun keatas biaya penuh</span>
                    </li>
                    <li>Biaya Tambahan WNA: <strong class="additional-cost-wna">Rp
                            {{ number_format($wnaCost, 0, ',', '.') }}</strong><br><span
                            class="fst-italic fs-6 text-info">Untuk WNA
                            dikenakan biaya tambahan /orang</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
