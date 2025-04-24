@extends('admin.admin_dashboard')
@section('admin')
    <!-- Content -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4>Jurnal Biaya - Booking: {{ $booking->code_booking ?? 'N/A' }}</h4>

            @if ($journals->isEmpty())
                <p>Tidak ada data jurnal untuk booking ini.</p>
            @else
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th>Akun</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($journals as $journal)
                            <tr style="background-color:#f5f5f5">
                                <td>{{ \Carbon\Carbon::parse($journal->date)->format('d M Y') }}</td>
                                <td colspan="4"><strong>{{ $journal->description }}</strong></td>
                            </tr>
                            @foreach ($journal->entries as $entry)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="text-start">{{ $entry->account->name ?? '-' }}</td>
                                    <td class="text-end">Rp {{ number_format($entry->debit, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($entry->credit, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    <!--/ Content End -->
@endsection
