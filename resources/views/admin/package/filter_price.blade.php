@extends('admin.admin_dashboard')
@section('admin')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="mb-4 row">
                <h5 class="mb-0 font-semibold">Search Price</h5>
                <p class="text-sm text-gray-600">Cari harga paket wisata berdasakan kriteria yang dipilih.</p>
                <hr class="my-2" />
            </div>

            <div class="mb-4 row">
                <form method="GET" action="{{ route('price.search') }}">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-1">Filter Paket Wisata</h6>
                            <p class="text-sm text-gray-600">Silakan isi kolom sesuai dengan yang di butuhkan</p>
                        </div>
                        <div class="d-flex align-content-center flex-wrap gap-4">
                            <label for="submit" class="form-label">&nbsp;</label>
                            <button type="submit" id="submit" class="btn btn-primary w-100">Tampilkan</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-2 col-sm-6 col-lg-3">
                            <div class="pb-4 d-flex justify-content-between align-items-start card-widget-1 pb-sm-0">
                                <label for="min_peserta" class="form-label">Min Peserta</label>
                                <input type="number" min="0" name="min_peserta" id="min_peserta"
                                    class="form-control" value="{{ request('min_peserta') }}">
                            </div>
                        </div>
                        <div class="mb-2 col-sm-6 col-lg-3">
                            <div class="pb-4 d-flex justify-content-between align-items-start card-widget-1 pb-sm-0">
                                <label for="max_peserta" class="form-label">Max Peserta</label>
                                <input type="number" min="0" name="max_peserta" id="max_peserta"
                                    class="form-control" value="{{ request('max_peserta') }}">
                            </div>
                        </div>
                        <div class="mb-2 col-sm-6 col-lg-3">
                            <div class="pb-4 d-flex justify-content-between align-items-start card-widget-1 pb-sm-0">
                                <label for="durasi_paket" class="form-label">Durasi Paket</label>
                                <select name="durasi_paket" id="durasi_paket" class="form-select" required>
                                    <option value="">-- Semua Durasi --</option>
                                    <option value="1" {{ request('durasi_paket') == '1' ? 'selected' : '' }}>1 Hari
                                    </option>
                                    <option value="2" {{ request('durasi_paket') == '2' ? 'selected' : '' }}>2 Hari
                                    </option>
                                    <option value="3" {{ request('durasi_paket') == '3' ? 'selected' : '' }}>3 Hari
                                    </option>
                                    <option value="4" {{ request('durasi_paket') == '4' ? 'selected' : '' }}>4 Hari
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="pb-4 d-flex justify-content-between align-items-start card-widget-1 pb-sm-0">
                                <label for="agen_name" class="form-label">Agen Name</label>
                                <select name="agen_name" id="agen_name" class="form-select" required>
                                    <option value="">-- Semua Agen --</option>
                                    @foreach ($agens as $agen)
                                        <option value="{{ $agen->id }}"
                                            {{ request('agen_name') == $agen->id ? 'selected' : '' }}>
                                            {{ $agen->username }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @if (isset($packages) && $packages->count() > 0)
                <div class="row p-3">
                    @foreach ($packages as $package)
                        <div class="card mb-4">
                            <div class="row mt-3">
                                <div class="col-sm-4 text-center align-content-center">
                                    <h5><b>Nama Paket</b></h5>
                                </div>
                                <div class="col-sm-8 mb-0 text-center align-content-center">
                                    <h4 class="text-info">{{ $package['name_package'] }}</h4>
                                </div>
                            </div>

                            <!-- Destinations -->
                            {{-- <div class="row mt-3">
                                <div class="col-12">
                                    <h5><b>Destinasi:</b></h5>
                                    <ul>
                                        @foreach ($package['destinations'] as $destination)
                                            <li>{{ $destination['name'] }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div> --}}

                            <!-- Facilities -->
                            {{-- <div class="row mt-3">
                                <div class="col-12">
                                    <h5><b>Fasilitas:</b></h5>
                                    <ul>
                                        @foreach ($package['facilities'] as $facility)
                                            <li>{{ $facility['name'] }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div> --}}

                            <!-- Prices -->
                            @if (isset($package['grouped_prices']) && !empty($package['grouped_prices']))
                                @foreach ($package['grouped_prices'] as $type => $pricesByParticipant)
                                    @if (!empty($pricesByParticipant))
                                        <div class="row mt-3">
                                            <div class="col-sm-12 text-center align-content-center">
                                                <h5><b>{{ strtoupper(str_replace(' ', ' - ', $type)) }}</b></h5>
                                            </div>
                                        </div>

                                        @foreach ($pricesByParticipant as $participantCount => $price)
                                            @if (isset($price['WithoutAccomodation']))
                                                <div class="row">
                                                    <div class="col-sm-3 my-1 align-content-center"><b>Peserta</b></div>
                                                    <div class="col-sm-3 my-1 align-content-center">{{ $participantCount }}
                                                        Pax</div>
                                                    <div class="col-sm-3 my-1 align-content-center"><b>Tanpa Penginapan</b>
                                                    </div>
                                                    <div class="col-sm-3 my-1 align-content-center">Rp
                                                        {{ number_format($price['WithoutAccomodation'], 0, ',', '.') }}
                                                        /orang</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3 my-1 align-content-center"><b>Homestay</b></div>
                                                    <div class="col-sm-3 my-1 align-content-center">Rp
                                                        {{ number_format($price['Homestay'] ?? 0, 0, ',', '.') }} /orang
                                                    </div>
                                                    <div class="col-sm-3 my-1 align-content-center"><b>Hotel *3</b></div>
                                                    <div class="col-sm-3 my-1 align-content-center">Rp
                                                        {{ number_format($price['ThreeStar'] ?? 0, 0, ',', '.') }} /orang
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                        @if (!$loop->last)
                                            <hr>
                                        @endif
                                    @endif
                                @endforeach
                            @else
                                <div class="row mt-3">
                                    <div class="col-12 text-center">
                                        <p class="text-danger">Harga tidak tersedia untuk kriteria yang dipilih</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @elseif(request()->hasAny(['min_peserta', 'max_peserta', 'durasi_paket', 'agen_name']))
                <div class="alert alert-warning">
                    Tidak ada paket yang ditemukan dengan kriteria yang dipilih.
                </div>
            @endif

        </div>
        <!-- End content -->
    </div>
    <!-- End content wrapper -->
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');

            form.addEventListener('submit', function(e) {
                const durasi = document.getElementById('durasi_paket').value;
                const agen = document.getElementById('agen_name').value;
                const minPeserta = document.getElementById('min_peserta').value;
                const maxPeserta = document.getElementById('max_peserta').value;

                if (!durasi) {
                    alert('Silakan pilih durasi paket');
                    e.preventDefault();
                    return;
                }

                if (!agen) {
                    alert('Silakan pilih agen');
                    e.preventDefault();
                    return;
                }

                if (!minPeserta && !maxPeserta) {
                    alert('Silakan isi minimal peserta atau maksimal peserta');
                    e.preventDefault();
                    return;
                }

                if (minPeserta && maxPeserta && parseInt(minPeserta) > parseInt(maxPeserta)) {
                    alert('Minimal peserta tidak boleh lebih besar dari maksimal peserta');
                    e.preventDefault();
                    return;
                }
            });
        });
    </script>
@endsection
