@extends('admin.admin_dashboard')
@section('admin')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4>All List Custom Data</h4>
        <div class="card mt-4">
            <div class="card-datatable table-responsive pt-0">
                <table id="example" class="datatables-ajax table" style="width:100%">
                    <thead>
                        <tr>
                            <th class="align-content-center text-center">#</th>
                            <th class="align-content-center text-center">Name</th>
                            <th class="align-content-center text-center">Agen</th>
                            <th class="align-content-center text-center">Duration</th>
                            <th class="align-content-center text-center">Total User</th>
                            <th class="align-content-center text-center">Price Perperson</th>
                            <th class="align-content-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customData as $key => $data)
                        <tr>
                            <td class="align-content-center text-center">{{ $key + 1 }}</td>
                            <td class="align-content-center text-center">{{ $data['package_name'] }}</td>
                            <td class="align-content-center text-center">{{ $data['agen_name'] }}</td>
                            <td class="align-content-center text-center">{{ $data['DurationPackage'] }} Days</td>
                            <td class="align-content-center text-center">{{ $data['participants'] }}</td>
                            <td class="align-content-center text-center">Rp {{ number_format($data['costPerPerson'], 0,
                                ',', '.') }}</td>
                            <td class="align-content-center">
                                <!-- Icon Dropdown -->
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="btn-group">
                                        <button type="button"
                                            class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if (Auth::user()->can('package.show'))
                                            <li><a class="dropdown-item text-warning" href="javascript:void(0)" data-id="{{ $data['id'] }}"><i class="ti ti-eye"></i>Show</a></li>
                                            @endif
                                            @if (Auth::user()->can('booking.add'))
                                            <li><a href="javascript:void(0)" class="dropdown-item text-success"> <i class="ti ti-shopping-cart-plus"></i> Booking
                                            </a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <!--/ Icon Dropdown -->
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No Custom Packages Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End Content -->
</div>


@endsection
