@extends('admin.admin_dashboard')
@section('admin')
    <!-- Content -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div
                class="row-gap-4 mb-6 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-0">Jurnal Biaya - Booking :<br><span class="text-primary">{{ $booking->code_booking ?? 'N/A' }}</span></h4> <h6 class="mt-1"><span class="text-xs badge bg-info text-uppercase">{{  $booking->status }}</span></h6>
                    <p class="mb-0">Jurnal biaya operasional sesuai dengan kode akun</p>                    
                </div>
                <div class="flex-wrap gap-4 d-flex align-content-center">                    
                    <a href="{{ route('admin.fix.journal.booking', ['id' => $booking->id]) }}" class="btn btn-primary">Fix HPP</a>
                    <a href="{{ route('all.expenses') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
            
            <div class="mb-4 card">
                <div class="card-body">
                    <h5>Ringkasan Keuangan:</h5>                                        
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered">
                                <tr>
                                    <th>Total Biaya (HPP)</th>
                                    <td class="text-end"><b>Rp {{ number_format($finance['total_cost'], 0, ',', '.') }}</b></td>                                    
                                    <th>Total Pendapatan</th>
                                    <td class="text-end"><b>Rp {{ number_format($finance['total_income'], 0, ',', '.') }}</b></td>
                                </tr>
                                <tr>                                    
                                    <th>HPP (%)</th>
                                    <td class="text-end"><b>{{ number_format($finance['hpp_percent'], 2, ',', '.') }}%</b></td>                                    
                                    <th>Margin</th>
                                    <td class="text-end"><b>Rp {{ number_format($finance['margin'], 0, ',', '.') }}</b></td>
                                </tr>                       
                        </table>
                    </div>
                    <hr class="my-8">
                    @if ($journals->isEmpty())
                        <p>Tidak ada data jurnal untuk booking ini.</p>
                    @else                
                    <div class="table-responsive text-nowrap">
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
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--/ Content End -->
@endsection
