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
                        <a class="nav-link active" href="{{ route('all.meals') }}">
                            <i class="ti ti-soup ti-sm me-1_5"></i>
                            <span class="align-middle">Meal</span>
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
                    <!-- Meals Tab -->
                    <form id="mydata" action="{{ route('meal.store') }}" method="POST">
                        @csrf
                        <div class="card mb-6">
                            <div class="card-header">
                                <h5 class="card-title m-0">Meal</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6 g-4">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label mb-1" for="mealPrice">Price</label>
                                        <input type="text" id="mealPrice" class="form-control numeral-mask"
                                            placeholder="50000" name="priceMeal" aria-label="Price" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label mb-1" for="meal_total">Total Meal</label>
                                        <input type="text" id="meal_total" class="form-control" placeholder="4"
                                            name="totalMeal" aria-label="Total Meal" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="meal_type">Meal Type</label>
                                        <select required id="meal_type" name="mealType"
                                            class="select2 form-select" data-allow-clear="true">
                                            <option value="">Select Type</option>
                                            <option value="1D">1 Day</option>
                                            <option value="2D">2 Day</option>
                                            <option value="3D">3 Day</option>
                                            <option value="4D">4 Day</option>
                                            <option value="5D">5 Day</option>
                                            <option value="Honeymoon">Honeymoon</option>
                                            <option value="Custom">Custom</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="meal_duration">Duration</label>
                                        <select required id="meal_duration" name="mealDuration"
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
                                    <div class="col-12 col-md-12">
                                        <label class="form-label" for="city_district">City / District</label>
                                        <select required id="city_district" name="cityOrDistrict_id"
                                            class="select2 form-select" data-allow-clear="true">
                                            <option value="">Select City / District</option>
                                            @foreach($regencies as $regency)
                                            <option value="{{ $regency->id }}">{{ $regency->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-4">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Index Meals Tab -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title m-0">Index Meals</h5>
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
                                                    <th class="align-content-center text-center">Type</th>
                                                    <th class="align-content-center text-center">Day</th>
                                                    <th class="align-content-center text-center">Total Meal</th>
                                                    <th class="align-content-center text-center">City / District</th>
                                                    <th class="align-content-center text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                @foreach ($meals as $key=> $mkn )
                                                <tr>
                                                    <td class="align-content-center text-center">#{{ $key+1 }}</td>
                                                    <td class="align-content-center text-center">{{ $mkn->price }}
                                                    </td>
                                                    <td class="align-content-center text-center">{{ $mkn->type }}
                                                    </td>
                                                    <td class="align-content-center text-center">{{ $mkn->duration }}
                                                    </td>
                                                    <td class="align-content-center text-center">{{ $mkn->num_meals }}</td>
                                                    <td class="align-content-center text-center">{{ $mkn->regency->name
                                                        }}
                                                    </td>
                                                    <td class="align-content-center text-center">
                                                        <div class="dropdown">
                                                            <button type="button"
                                                                class="btn p-0 dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item button" data-bs-toggle="modal"
                                                                    data-bs-target="#enableMeal"
                                                                    data-id="{{ $mkn->id }}"
                                                                    data-mealPrice="{{ $mkn->price }}"
                                                                    data-mealType="{{ $mkn->type }}"
                                                                    data-mealDuration="{{ $mkn->duration }}"
                                                                    data-mealTotal="{{ $mkn->num_meals }}"
                                                                    data-city="{{ $mkn->regency_id }}">
                                                                    <i class="ti ti-pencil me-1"></i> Edit
                                                                </a>
                                                                <a class="dropdown-item button text-danger delete-confirm"
                                                                    data-id="{{ $mkn->id }}"
                                                                    data-url="{{ route('delete.meal', $mkn->id) }}"><i
                                                                        class="ti ti-trash me-1"></i> Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
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

<!-- Meals Modal -->
<div class="modal fade" id="enableMeal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="mb-2">Edit Meal</h4>
                </div>
                <form id="enableMealForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row mb-6 g-4">
                            <div class="col-12 col-md-6">
                                <label class="form-label mb-1" for="meal-Price">Price</label>
                                <input type="text" id="meal-Price" class="form-control numeral-mask"
                                    placeholder="50000" name="priceMeal" aria-label="Price" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label mb-1" for="meal-total">Total Meal</label>
                                <input type="text" id="meal-total" class="form-control" placeholder="4"
                                    name="totalMeal" aria-label="Total Meal" />
                            </div>
                            <div>
                                <label class="form-label" for="meal-type">Meal Type</label>
                                <select required id="meal-type" name="mealType"
                                    class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select Type</option>
                                    <option value="1D" {{ isset($meal) && $meals->type == '1D' ? 'selected' : '' }}>1 Day</option>
                                    <option value="2D" {{ isset($meal) && $meals->type == '2D' ? 'selected' : '' }}>2 Day</option>
                                    <option value="3D" {{ isset($meal) &&  $meals->type == '3D' ? 'selected' : '' }}>3 Day</option>
                                    <option value="4D" {{ isset($meal) && $meals->type == '4D' ? 'selected' : '' }}>4 Day</option>
                                    <option value="5D" {{ isset($meal) && $meals->type == '5D' ? 'selected' : '' }}>5 Day</option>
                                    <option value="Honeymoon" {{ isset($meal) && $meals->type == 'Honeymoon' ? 'selected' : '' }}>Honeymoon</option>
                                    <option value="Custom" {{ isset($meal) && $meals->type == 'Custom' ? 'selected' : '' }}>Custom</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="meal-duration">Duration</label>
                                <select required id="meal-duration" name="mealDuration"
                                    class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select duration</option>
                                    <option value="1" {{ isset($meal) && $meals->duration == '1' ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ isset($meal) && $meals->duration == '2' ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ isset($meal) && $meals->duration == '3' ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ isset($meal) && $meals->duration == '4' ? 'selected' : '' }}>4</option>
                                    <option value="5" {{ isset($meal) && $meals->duration == '5'? 'selected' : '' }}>5</option>
                                    <option value="6" {{ isset($meal) && $meals->duration == '6'? 'selected' : '' }}>6</option>
                                </select>

                            </div class="col-12 col-md-6">
                            <div class="col-12 col-md-12">
                                <label class="form-label" for="city-district">City / District</label>
                                <select required id="city-district" name="cityOrDistrict_id"
                                    class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select City / District</option>
                                    @foreach($regencies as $regency)
                                        <option value="{{ $regency->id }}"
                                            {{ isset($meals->cityOrDistrict_id) && $meals->cityOrDistrict_id == $regency->id ? 'selected' : '' }}>
                                            {{ $regency->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
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
<!--/ Meals Modal -->

@endsection
