@extends('admin.admin_dashboard')
@section('admin')
    <!-- Content -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="mb-4 row">
                <h5 class="mb-0 font-semibold">Laporan Buku Besar</h5>
                <p class="text-sm text-gray-600">Laporan ini menampilkan semua transaksi yang terjadi pada akun-akun
                    yang ada di sistem.</p>
                <hr class="my-2" />
            </div>

            <div class="mb-4 row">
                <h6 class="mb-1">Filter Laporan</h6>
                <p class="text-sm text-gray-600">Silakan pilih tanggal dan akun yang ingin ditampilkan pada laporan
                    ini.</p>
                <form method="GET" action="">
                    <div class="row">
                        <div class="mb-2 col-sm-6 col-lg-3">
                            <div class="pb-4 d-flex justify-content-between align-items-start card-widget-1 pb-sm-0">
                                <label for="start_date" class="form-label">Dari Tanggal</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ $filters['start_date'] ?? '' }}" class="form-control">
                            </div>
                        </div>
                        <div class="mb-2 col-sm-6 col-lg-3">
                            <div class="pb-4 d-flex justify-content-between align-items-start card-widget-1 pb-sm-0">
                                <label for="end_date" class="form-label">Sampai Tanggal</label>
                                <input type="date" name="end_date" id="end_date"
                                    value="{{ $filters['end_date'] ?? '' }}" class="form-control">
                            </div>
                        </div>
                        <div class="mb-2 col-sm-6 col-lg-3">
                            <div class="pb-4 d-flex justify-content-between align-items-start card-widget-1 pb-sm-0">
                                <label for="account_id" class="form-label">Daftar Akun</label>
                                <select name="account_id" id="account_id" class="form-select">
                                    <option value="">-- Semua Akun --</option>
                                    @foreach ($accounts as $acc)
                                        <option value="{{ $acc->id }}"
                                            {{ ($filters['account_id'] ?? '') == $acc->id ? 'selected' : '' }}>
                                            {{ $acc->code }} - {{ $acc->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="pb-4 d-flex justify-content-between align-items-start card-widget-1 pb-sm-0">
                                <label for="submit" class="form-label">&nbsp;</label>
                                <button type="submit" id="submit" class="btn btn-primary w-100">Tampilkan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row">
                <!-- Bordered Table -->
                @foreach ($ledgers as $ledger)
                    <div class="mb-4 card">
                        <h5 class="card-header">{{ $ledger['account']->code }} - {{ $ledger['account']->name }}</h5>
                        <div class="card-body">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-content-center">Tanggal</th>
                                            <th class="text-center align-content-center">Deskripsi</th>
                                            <th class="text-center align-content-center">Debit</th>
                                            <th class="text-center align-content-center">Kredit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ledger['entries'] as $entry)
                                            <tr>
                                                <td class="text-center align-content-center">{{ $entry->journal->date }}</td>
                                                <td class="text-start align-content-center">{{ $entry->journal->description }}</td>
                                                <td class="text-end align-content-center">{{ number_format($entry->debit, 0, ',', '.') }}</td>
                                                <td class="text-end align-content-center">{{ number_format($entry->credit, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-center align-content-center" >Total :</th>
                                            <th class="text-end align-content-center">{{ number_format($ledger['debit'], 0, ',', '.') }}</th>
                                            <th class="text-end align-content-center">{{ number_format($ledger['credit'], 0, ',', '.') }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="text-center align-content-center">Saldo Akhir :</th>
                                            <th colspan="2" class="text-end align-content-center">{{ number_format($ledger['saldo'], 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!--/ Bordered Table -->
            </div>
        </div>
    </div>
    <!--/ Content End -->
@endsection
