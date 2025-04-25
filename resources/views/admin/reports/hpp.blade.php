@extends('admin.admin_dashboard')
@section('admin')
    <!-- Content -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="mb-0">Pilih Range Laporan :</h4>
            <div class="row">
                <form method="GET" action="{{ route('report.hpp') }}">
                    <div class="col-12 col-md-3"></div>
                    <label for="range">Pilih Rentang Tanggal:</label>
                    <select name="range" onchange="this.form.submit()">
                        <option value="week" {{ $range == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ $range == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="year" {{ $range == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                        <option value="custom" {{ $range == 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                    @if ($range == 'custom')
                        <div class="col-12 col-md-3">
                            <label for="start">Tanggal Mulai:</label>
                            <input type="date" name="start" value="{{ $start->format('Y-m-d') }}">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="end">Tanggal Akhir:</label>
                            <input type="date" name="end" value="{{ $end->format('Y-m-d') }}">
                        </div>
                    @else
                        <div class="col-12 col-md-3">
                            <label for="submit">&nbsp;</label>
                            <button type="submit" class="btn btn-primary">Tampilkan</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
        <div class="mb-4 card">
            <div class="card-body">
                <h5>Laporan HPP</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Range Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hpp as $item)
                                <tr></tr>
                                <tr style="background-color:#f5f5f5">
                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                    <td colspan="4"><strong>{{ $item->description }}</strong></td>
                                </tr>
                                @foreach ($item->entries as $entry)
                                    <tr>
                                        <td></td>
                                        <td>{{ $entry->description }}</td>
                                        <td>{{ $entry->account->name }}</td>
                                        <td class="text-end">{{ number_format($entry->debit, 0, ',', '.') }}</td>
                                        <td class="text-end">{{ number_format($entry->credit, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
