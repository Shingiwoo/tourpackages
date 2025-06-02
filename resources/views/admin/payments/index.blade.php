@extends('admin.admin_dashboard')
@section('admin')
    <!-- Content -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div
                class="row-gap-4 mb-6 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Payment Index</h4>
                    <p class="mb-0">Payment list by Booking</p>
                </div>
                <div class="flex-wrap gap-4 d-flex align-content-center">
                    <div class="gap-4 d-flex">
                        <a href="{{ route('all.bookings') }}" type="button" class="btn btn-label-secondary">Back</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card">
                    <div class="pt-0 card-datatable table-responsive">
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="card-header flex-column flex-md-row">
                                <div class="text-center head-label">
                                    <h5 class="mb-0 card-title">Payment List</h5>
                                </div>
                            </div>
                            <div class="card-datatable text-nowrap">
                                <table id="example" class="table datatables-ajax">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-content-center text-primary">No</th>
                                            <th class="text-center align-content-center text-primary">Jatuh Tempo</th>
                                            <th class="text-center align-content-center text-primary">Jumlah</th>
                                            <th class="text-center align-content-center text-primary">Type</th>
                                            <th class="text-center align-content-center text-primary">Status</th>
                                            <th class="text-center align-content-center text-primary">Metode</th>
                                            <th class="text-center align-content-center text-primary">Tanggal Pembayaran
                                            </th>
                                            @if (Auth::user()->can('payment.actions'))
                                                <th class="text-start align-content-center text-primary">Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($payments as $data)
                                            <tr>
                                                <td class="text-center align-content-center">{{ $loop->iteration }}</td>
                                                <td class="text-center align-content-center">
                                                    {{ \Carbon\Carbon::parse($data->payment_due_date)->format('j F Y') }}
                                                </td>
                                                <td class="text-center align-content-center">Rp
                                                    {{ number_format($data->ammount, 0, ',', '.') }}</td>
                                                <td class="text-center align-content-center">
                                                    <span class="text-uppercase">{{ $data->type }}</span>
                                                    @if ($data->type == 'dp')
                                                        <span class="badge bg-label-warning">ke -
                                                            {{ $data->dp_installment }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-content-center"><span
                                                        class="text-uppercase">{{ $data->status }}</span></td>
                                                <td class="text-center align-content-center"><span
                                                        class="text-uppercase">{{ $data->method }}</span></td>
                                                <td class="text-center align-content-center">
                                                    {{ $data->payment_at ?? 'Belum Dibayar' }}</td>
                                                @if (Auth::user()->can('payment.actions'))
                                                    <td class="align-content-center">
                                                        <div class="col-lg-3 col-sm-6 col-12">
                                                            <div class="btn-group">
                                                                <button type="button"
                                                                    class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="ti ti-dots-vertical"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    @if (Auth::user()->can('payment.edit'))
                                                                        <li>
                                                                            <a href="{{ route('bookings.payments.edit', [$booking->id, $data->id]) }}"
                                                                                class="dropdown-item text-info">
                                                                                <span class="ti ti-pencil ti-md"></span>
                                                                                Edit
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                    @if (Auth::user()->can('payment.show'))
                                                                        @if ($data->status === 'waiting' && $data->method === 'transfer')
                                                                            <li>
                                                                                <a href="{{ route('payments.show', [$booking->id, $data->id]) }}"
                                                                                    type="submit"
                                                                                    class="dropdown-item text-success">
                                                                                    <i class="ti ti-search ti-md"></i>
                                                                                    Show
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    @endif
                                                                    @if (Auth::user()->can('payment.cancel'))
                                                                        @if ($data->status !== 'waiting')
                                                                        <form action="{{ route('bookings.payments.cancel', [$booking->id, $data->id]) }}" method="POST">
                                                                            @csrf          
                                                                            <li>
                                                                                <a href=""
                                                                                    type="submit"
                                                                                    class="dropdown-item text-warning">
                                                                                    <i class="ti ti-brand-xamarin ti-md"></i>
                                                                                    Cancel
                                                                                </a>
                                                                            </li>
                                                                        </form>
                                                                        @endif
                                                                    @endif
                                                                    @if ($booking->status !== 'finished')
                                                                        @if (Auth::user()->can('payment.delete'))
                                                                            <li>
                                                                                <form
                                                                                    action="{{ route('payments.destroy', [$booking->id, $data->id]) }}"
                                                                                    method="POST">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit"
                                                                                        class="dropdown-item text-danger">
                                                                                        <i class="ti ti-trash ti-md"></i>
                                                                                        Delete
                                                                                    </button>
                                                                                </form>
                                                                            </li>
                                                                        @endif
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection
