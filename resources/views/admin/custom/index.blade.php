@extends('admin.admin_dashboard')
@section('admin')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="mb-1">Custom Package</h4>

        <p class="mb-6">
            Menghitung dengan cepat harga paket wisata mulai 1 hari - 4 hari <br>
            sesuai dengan biaya dan detail yang isikan.
        </p>
        <div class="row g-6">
            <div class="col-xxl-4 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Detail Custom Package</h5>
                        </div>
                    </div>
                    <form action="">
                        <div class="card-body" style="position: relative;">
                            <div class="row">
                                <div class="col mb-6">
                                    <label class="form-label" for="destinations">Destination</label>
                                    <select id="destinations" name="destinations[]" class="select2 form-select" multiple>
                                        @foreach ($destinations as $destination)
                                        <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="form-label" for="vehicle">Vehicle</label>
                                <select id="vehicle" name="vehicle" class="select2 form-select" multiple>
                                    @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->name }} | Min: {{ $vehicle->capacity_min
                                        }} | Max: {{ $vehicle->capacity_max }} | City: {{ $vehicle->regency->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col mb-6">
                                    <label class="form-label" for="duration_package">Duration</label>
                                    <select required id="duration_package" name="DurationPackage"
                                        class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select Duration</option>
                                        <option value="1">One Day</option>
                                        <option value="2">Two Days</option>
                                        <option value="3">Three Days</option>
                                        <option value="4">Four Days</option>
                                        <option value="5">Four Days</option>
                                    </select>
                                </div>
                                <div class="col mb-6">
                                    <label class="form-label" for="night">Night</label>
                                    <select required id="night" name="nights" class="select2 form-select"
                                        data-allow-clear="true">
                                        <option value="0" selected>0</option>
                                        <option value="1">1 Night</option>
                                        <option value="2">2 Night</option>
                                        <option value="3">3 Night</option>
                                        <option value="4">4 Night</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-6">
                                    <label class="form-label" for="otherFee">Other Fee</label>
                                    <input type="text" id="otherFee" class="form-control numeral-mask"
                                        placeholder="500000" name="otherFee" aria-label="Other Fee" required />
                                </div>
                                <div class="col mb-6">
                                    <label class="form-label" for="reservedFee">Reserved Fee</label>
                                    <input type="text" id="reservedFee" class="form-control numeral-mask2"
                                        placeholder="500000" name="reservedFee" aria-label="Reserved Fee" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-6">
                                    <label class="form-label" for="hotelPrice">Hotel</label>
                                    <input type="text" id="hotelPrice" class="form-control numeral-mask3"
                                        placeholder="500000" name="hotelPrice" aria-label="Hotel" required />
                                </div>
                                <div class="col mb-6">
                                    <label class="form-label" for="capacityHotel">Capacity</label>
                                    <input type="number" class="form-control" id="capacityHotel"
                                        placeholder="5" max="50" name="capacityHotel" aria-label="Capacity Hotel" required />
                                </div>
                                <div class="col mb-6">
                                    <label class="form-label" for="totalUser">Total User</label>
                                    <input type="number" class="form-control" id="totalUser"
                                        placeholder="5" max="999" name="totalUser" aria-label="Total User" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-6">
                                    <label class="form-label" for="facility">Facility</label>
                                    <select id="facility" name="facilities[]" class="select2 form-select" multiple>
                                        @foreach ($facilities as $facility)
                                        <option value="{{ $facility->id }}">{{ $facility->name }} => {{ $facility->type }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xxl-4 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Hasil Perhitungan :</h5>
                        </div>
                    </div>
                    <div class="card-body" style="position: relative;">
                        <div class="card-datatable text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="fw-medium mx-2 text-center" style="width: 40%">
                                            Detail
                                        </th>
                                        <th  class="fw-medium mx-2 text-center">
                                            Total Harga
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th><i class="ti ti-car ti-lg mx-2"></i>Transport</th>
                                        <td class="text-end">Rp <span id="totalDisplay"></span></td>
                                    </tr>
                                    <tr>
                                        <th><i class="ti ti-parking-circle ti-lg mx-2"></i>Biaya Parkir</th>
                                        <td class="text-end">Rp <span id="biayaParkir"></span></td>
                                    </tr>
                                    <tr>
                                        <th><i class="ti ti-ticket ti-lg mx-2"></i>Tiket Masuk</th>
                                        <td class="text-end">Rp <span id="tiketmasuk"></span></td>
                                    </tr>
                                    <tr>
                                        <th><i class="ti ti-building-skyscraper ti-lg mx-2"></i>Penginapan</th>
                                        <td class="text-end">Rp <span id="penginapan"></span></td>
                                    </tr>
                                    <tr>
                                        <th><i class="ti ti-devices-dollar ti-lg mx-2"></i>Biaya Lain</th>
                                        <td class="text-end">Rp <span id="biayaLain"></span></td>
                                    </tr>
                                    <tr>
                                        <th><i class="ti ti-clock-dollar ti-lg mx-2"></i>Biaya Jasa</th>
                                        <td class="text-end">Rp <span id="biayaJasa"></span></td>
                                    </tr>
                                    <tr>
                                        <th><i class="ti ti-home-infinity ti-lg mx-2"></i>Fasilitas</th>
                                        <td class="text-end">Rp <span id="biayaFasilitas"></span></td>
                                    </tr>
                                    <tr>
                                        <th><i class="ti ti-receipt ti-lg mx-2"></i><strong>Total Biaya</strong></th>
                                        <td class="text-end"><strong>Rp <span id="totalBiaya"></span></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2">
                            <p>Jadi untuk biaya Perorang : <b>Rp 560.000 /orang</b><br>
                                Dengan minimal jumlah peserta 5 dewasa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
</div>


@endsection
