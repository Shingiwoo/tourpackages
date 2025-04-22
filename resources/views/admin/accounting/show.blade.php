@extends('admin.admin_dashboard')
@section('admin')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="py-3 mb-4 fw-bold">Detail Biaya Pengeluaran</h5>
        <div class="mb-4 card">
            <div class="card-body">
                <div class="text-center row align-content-center">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label text-info">Kode Booking :</label>
                            <h5 class="form-control-static"><b>{{ $booking->code_booking }}</b></h5>
                        </div>
                    </div>
                </div>
                <div class="text-center row align-content-center">
                    <div class="col-6 col-4">
                        <div class="mb-3">
                            <label class="form-label text-info">Nama Paket :</label>
                            <p class="form-control-static"><b>{{ $booking->package_name ?? 'N/A' }}</b></p>
                        </div>
                    </div>
                    <div class="col-6 col-4">
                        <div class="mb-3">
                            <label class="form-label text-info">Status :</label>
                             <p class="form-control-static text-uppercase"><b>{{ $booking->status }}</b></p>
                        </div>
                    </div>
                </div>
                <hr>
                <h5>Rincian Biaya:</h5>
                <ul class="mb-3 list-group">
                    @foreach($expenses as $expense)
                        <li class="list-group-item d-flex justify-content-between align-items-start align-content-center">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">{{ $expense->description }}</div>
                                Account: {{ $expense->name }}
                            </div>
                            <span class="badge bg-primary rounded-pill">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="text-end">
                    <h5>Total Biaya: Rp {{ number_format($total_cost, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>
         <a href="{{ route('all.expenses') }}" class="btn btn-secondary">Kembali ke Daftar Biaya</a>
    </div>
</div>
@endsection