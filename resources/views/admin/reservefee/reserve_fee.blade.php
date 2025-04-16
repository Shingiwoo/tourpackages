@extends('admin.admin_dashboard')
@section('admin')


<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-6">
        <!-- Navigation -->
        <div class="col-12 col-lg-4">
            <div class="d-flex justify-content-between flex-column mb-4 mb-md-0">
                <h5 class="mb-4">Other Service</h5>
                <ul class="nav nav-align-left nav-pills flex-column">
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="{{ route('all.service') }}">
                            <i class="ti ti-user-screen ti-sm me-1_5"></i>
                            <span class="align-middle">Service Fee</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="{{ route('all.crew') }}">
                            <i class="ti ti-brand-teams ti-sm me-1_5"></i>
                            <span class="align-middle">Crew</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="{{ route('all.agen.fee') }}">
                            <i class="ti ti-user-pentagon ti-sm me-1_5"></i>
                            <span class="align-middle">Agen Fee</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="{{ route('all.facility') }}">
                            <i class="ti ti-home-infinity ti-sm me-1_5"></i>
                            <span class="align-middle">Facility</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="{{ route('all.meals') }}">
                            <i class="ti ti-soup ti-sm me-1_5"></i>
                            <span class="align-middle">Meal</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link active" href="{{ route('all.reservefee') }}">
                            <i class="ti ti-creative-commons-nc ti-sm me-1_5"></i>
                            <span class="align-middle">Reserve Fee</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /Navigation -->

        <!-- Options -->
        <div class="col-12 col-lg-8 pt-6 pt-lg-0">
            <div class="tab-content p-0">

                <div class="tab-pane fade show active" role="tabpanel">
                    <!-- Reserve Fee Input -->
                    @if (Auth::user()->can('serive.add'))
                    <form id="mydata" action="{{ route('reservefee.store') }}" method="POST">
                        @csrf
                        <div class="card mb-6">
                            <div class="card-header">
                                <h5 class="card-title m-0">Reserve Fee</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6 g-4">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label mb-1" for="reservefeePrice">Price</label>
                                        <input type="text" id="reservefeePrice" class="form-control numeral-mask"
                                            placeholder="50000" name="priceReserveFee" aria-label="Price" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label mb-1" for="MinUser-reservefee">Min Participant</label>
                                        <input type="number" id="MinUser-reservefee" class="form-control"
                                            placeholder="3" name="ReserveFeeMinUser" aria-label="Minimal Participant" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label mb-1" for="MaxUser-reservefee">Max Participant</label>
                                        <input type="number" id="MaxUser-reservefee" class="form-control"
                                            placeholder="3" name="ReserveFeeMaxUser" aria-label="Maximal Participant" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="reservefee_duration">Duration</label>
                                        <select required id="reservefee_duration" name="ReserveFeeDuration"
                                            class="select2 form-select" data-allow-clear="true">
                                            <option value="">Select Duration</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-4">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @endif
                    <!-- Index Reserve Fee Tab -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title m-0">Index Reserve Fee</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-6">
                                <div class="col-12 col-md-12">
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="align-content-center text-center">SL</th>
                                                    <th class="align-content-center text-center">Price</th>
                                                    <th class="align-content-center text-center">Day</th>
                                                    <th class="align-content-center text-center">User</th>
                                                    @if (Auth::user()->can('service.action'))
                                                    <th class="align-content-center text-center">Actions</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                @foreach ($reservefees as $key=> $fee )
                                                <tr>
                                                    <td class="align-content-center text-center">#{{ $key+1 }}</td>
                                                    <td class="align-content-center text-center">Rp {{ number_format($fee->price, 0, ',', '.')  }}
                                                    </td>
                                                    </td>
                                                    <td class="align-content-center text-center">{{ $fee->duration }}
                                                    </td>
                                                    <td class="align-content-center text-center">
                                                    Min : {{ $fee->min_user }}<br>Max : {{ $fee->max_user }}
                                                    </td>
                                                    @if (Auth::user()->can('service.action'))
                                                    <td class="align-content-center text-center">
                                                        <div class="dropdown">
                                                            <button type="button"
                                                                class="btn p-0 dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                @if (Auth::user()->can('service.edit'))
                                                                <a class="dropdown-item button" data-bs-toggle="modal"
                                                                    data-bs-target="#enableReserveFee"
                                                                    data-id="{{ $fee->id }}"
                                                                    data-reservefeePrice="{{ $fee->price }}"
                                                                    data-ReserveFeeDuration="{{ $fee->duration }}"
                                                                    data-ReserveFeeMinUser="{{ $fee->min_user }}"
                                                                    data-ReserveFeeMaxUser="{{ $fee->max_user }}">
                                                                    <i class="ti ti-pencil me-1"></i> Edit
                                                                </a>
                                                                @endif
                                                                @if (Auth::user()->can('service.delete'))
                                                                <a class="dropdown-item button text-danger delete-confirm"
                                                                    data-id="{{ $fee->id }}"
                                                                    data-url="{{ route('delete.reservefee', $fee->id) }}"><i
                                                                        class="ti ti-trash me-1"></i> Delete</a>
                                                                @endif
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
        </div>
        <!-- /Options-->
    </div>
</div>
<!-- / Content -->

<!-- Reserve Fee Modal -->
<div class="modal fade" id="enableReserveFee" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="mb-2">Edit Reserve Fee</h4>
                </div>
                <form id="enableReserveFeeForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row mb-6 g-4">
                            <div class="col-12 col-md-6">
                                <label class="form-label mb-1" for="reservefee-Price">Price</label>
                                <input type="text" id="reservefee-Price" class="form-control numeral-mask2"
                                    placeholder="50000" name="priceReserveFee" aria-label="Price" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label mb-1" for="reservefee-MinUser">Min Participant</label>
                                <input type="number" id="reservefee-MinUser" class="form-control"
                                    placeholder="3" name="ReserveFeeMinUser" aria-label="Minimal Participant" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label mb-1" for="reservefee-MaxUser">Min Participant</label>
                                <input type="number" id="reservefee-MaxUser" class="form-control"
                                    placeholder="3" name="ReserveFeeMaxUser" aria-label="Maximal Participant" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="reservefee-duration">Duration</label>
                                <select required id="reservefee-duration" name="ReserveFeeDuration"
                                    class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select duration</option>
                                    <option value="1" {{ isset($reservefee) && $reservefees->duration == '1' ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ isset($reservefee) && $reservefees->duration == '2' ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ isset($reservefee) && $reservefees->duration == '3' ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ isset($reservefee) && $reservefees->duration == '4' ? 'selected' : '' }}>4</option>
                                    <option value="5" {{ isset($reservefee) && $reservefees->duration == '5'? 'selected' : '' }}>5</option>
                                    <option value="6" {{ isset($reservefee) && $reservefees->duration == '6'? 'selected' : '' }}>6</option>
                                </select>
                            </div class="col-12 col-md-6">
                        </div>
                        <div class="d-flex justify-content-end gap-4">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!--/ Reserve Fee Modal -->

@endsection
