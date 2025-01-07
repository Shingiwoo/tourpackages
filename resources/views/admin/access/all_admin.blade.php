@extends('admin.admin_dashboard')
@section('admin')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="mb-1">User With Role List</h4>

        <p class="mb-6">
            A role provided access to predefined menus and features so that depending on <br />
            assigned role an administrator can have access to what user needs.
        </p>
        <div class="d-flex justify-content-between flex-wrap gap-3 me-12">
            <!-- Super Admin -->
            <div class="d-flex align-items-center gap-3 me-6 me-sm-0">
              <div class="avatar avatar-lg">
                <div class="avatar-initial bg-label-primary rounded">
                  <div>
                    <img src="{{ asset('assets/svg/icons/laptop.svg') }}" alt="paypal" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="content-right">
                <p class="mb-0 fw-medium">Super Admin</p>
                <h4 class="text-primary mb-0">01</h4>
              </div>
            </div>
            <!-- Admin -->
            <div class="d-flex align-items-center gap-3">
              <div class="avatar avatar-lg">
                <div class="avatar-initial bg-label-info rounded">
                  <div>
                    <img src="{{ asset('assets/svg/icons/lightbulb.svg') }}" alt="Lightbulb" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="content-right">
                <p class="mb-0 fw-medium">Admin</p>
                <h4 class="text-info mb-0">02</h4>
              </div>
            </div>
            <!-- Accounting -->
            <div class="d-flex align-items-center gap-3">
              <div class="avatar avatar-lg">
                <div class="avatar-initial bg-label-success rounded">
                  <div>
                    <img src="{{ asset('assets/svg/icons/calculator.svg') }}" alt="Check" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="content-right">
                <p class="mb-0 fw-medium">Accounting</p>
                <h4 class="text-success mb-0">01</h4>
              </div>
            </div>
            <!-- Tour Planner -->
            <div class="d-flex align-items-center gap-3">
              <div class="avatar avatar-lg">
                <div class="avatar-initial bg-label-danger rounded">
                  <div>
                    <img src="{{ asset('assets/svg/icons/map-route.svg') }}" alt="Check" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="content-right">
                <p class="mb-0 fw-medium">Tour Planner</p>
                <h4 class="text-danger mb-0">04</h4>
              </div>
            </div>
            <!-- Agen -->
            <div class="d-flex align-items-center gap-3">
              <div class="avatar avatar-lg">
                <div class="avatar-initial bg-label-warning rounded">
                  <div>
                    <img src="{{ asset('assets/svg/icons/user-hexagon.svg') }}" alt="Check" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="content-right">
                <p class="mb-0 fw-medium">Agen</p>
                <h4 class="text-warning mb-0">14</h4>
              </div>
            </div>
            <!-- Button -->
            @if (Auth::user()->can('admin.add'))
            <div class="d-flex align-items-center gap-3">
              <div class="content-right">
                <a href="{{ route('add.admin') }}" class="btn btn-secondary create-new btn-primary waves-effect waves-light">
                  <span><i class="ti ti-plus me-sm-1"></i>
                    <span class="d-none d-sm-inline-block">Add Admin</span>
                  </span>
                </a>
              </div>
            </div>
            @endif
          </div>
        <!-- Role cards -->
        <div class="row g-6">
            <div class="col-12">
                <h4 class="mt-6 mb-1">Total users as admin with their roles</h4>
                <p class="mb-0">Find all of administrator accounts and their associate roles.</p>
            </div>
            <div class="col-12">
                <!-- Role Table -->
                <div class="card text-nowrap">
                    <div class="card-datatable table-responsive">
                        <table id="example" class="datatables-ajax table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="align-content-center text-center">#</th>
                                    <th class="align-content-center text-center">Photo</th>
                                    <th class="align-content-center text-center">Name</th>
                                    <th class="align-content-center text-center">Username</th>
                                    <th class="align-content-center text-center">Email</th>
                                    <th class="align-content-center text-center">Phone</th>
                                    <th class="align-content-center text-center">Role</th>
                                    @if (Auth::user()->can('admin.action'))
                                    <th class="align-content-center text-center">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alladmin as $key=> $item )
                                <tr>
                                    <td class="align-content-center text-center">{{ $key+1 }}</td>
                                    <td class="align-content-center text-center">
                                        @if($item->status === 'active')
                                            <div class="avatar me-4 avatar-online">
                                                <img src="{{ !empty($item->photo) ? asset('storage/profile/'.$item->photo) : asset('assets/img/avatars/no_image.jpg') }}" alt="Avatar" class="rounded-circle">
                                            </div>
                                        @else
                                            <div class="avatar me-4 avatar-busy">
                                                <img src="{{ !empty($item->photo) ? asset('storage/profile/'.$item->photo) : asset('assets/img/avatars/no_image.jpg') }}" alt="Avatar" class="rounded-circle">
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-content-center text-center">{{ $item->name }}</td>
                                    <td class="align-content-center text-center">{{ $item->username }}</td>
                                    <td class="align-content-center text-center">{{ $item->email }}</td>
                                    <td class="align-content-center text-center">+62{{ $item->phone }}</td>
                                    <td class="align-content-center text-center">
                                        @foreach ($item->roles as $role )

                                            @if ($role->name === 'Super Admin')
                                                <span class="badge rounded-pill bg-warning text-uppercase"><i class="ti ti-shield me-2"></i>{{ $role->name}}</span>
                                            @elseif ($role->name === 'Admin')
                                                <span class="badge rounded-pill bg-danger text-uppercase"><i class="ti ti-adjustments-search me-2"></i>{{ $role->name }}</span>
                                            @elseif ($role->name === 'Accounting')
                                                <span class="badge rounded-pill bg-success text-uppercase"><i class="ti ti-calculator me-2"></i>{{ $role->name }}</span>
                                            @elseif ($role->name === 'Tour Planer')
                                                <span class="badge rounded-pill bg-info text-uppercase"><i class="ti ti-lock-access me-2"></i>{{ $role->name }}</span>
                                            @endif

                                        @endforeach

                                    </td>
                                    @if (Auth::user()->can('admin.action'))
                                    <td class="align-content-center text-center">
                                        <!-- Icon Dropdown -->
                                        <div class="col-sm-3 col-sm-6 col-sm-12">
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @if (Auth::user()->can('admin.edit'))
                                                    <li><a class="dropdown-item text-warning" href="{{ route('edit.admin', $item->id )}}"><i class="ti ti-edit"></i> Edit</a>
                                                    </li>
                                                    @endif
                                                    @if (Auth::user()->can('admin.delete'))
                                                    <li><a href="javascript:void(0)" class="dropdown-item text-danger delete-confirm" data-id="{{ $item->id }}" data-url="{{ route('delete.admin', $item->id) }}"> <i class="ti ti-trash"></i> Delete
                                                     </a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        <!--/ Icon Dropdown -->
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
                <!--/ Role Table -->
            </div>
        </div>
        <!--/ Role cards -->
    </div>
    <!-- / Content -->


</div>
@endsection
